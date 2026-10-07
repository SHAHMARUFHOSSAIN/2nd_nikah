<?php

namespace App\Livewire\Member\Profile;

use App\Models\ProfilePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Photos extends Component
{
    use WithFileUploads;

    public const MAX_PHOTOS = 6;

    /**
     * @var array
     */
    public $newPhotos = [];

    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    protected function rules(): array
    {
        return [
            'newPhotos.*' => ['image', 'mimes:jpeg,jpg,png,webp,heic,heif', 'max:10240'],
        ];
    }

    protected $messages = [
        'newPhotos.*.image' => 'Uploaded files must be valid images.',
        'newPhotos.*.mimes' => 'Only JPG, PNG, WEBP, and HEIC images are supported.',
        'newPhotos.*.max' => 'Images must not exceed 10MB in size.',
    ];

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }

        // Lazy sync: if user has a profile_photo_path on memberProfile but no ProfilePhoto record, create it cleanly
        $profile = $user->memberProfile;
        if ($profile && $profile->profile_photo_path && $user->profilePhotos()->count() === 0) {
            if (Storage::disk('public')->exists($profile->profile_photo_path)) {
                $user->profilePhotos()->create([
                    'path' => $profile->profile_photo_path,
                    'is_primary' => true,
                    'sort_order' => 1,
                ]);
            }
        }

        $user->syncPrimaryPhotoToMemberProfile();
    }

    public function savePhotos(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $user = Auth::user();
        if (! $user) {
            return;
        }

        if (empty($this->newPhotos)) {
            $this->errorMessage = 'Please select at least one photo to upload.';
            return;
        }

        $this->validate();

        $existingCount = $user->profilePhotos()->count();
        $incomingCount = count($this->newPhotos);

        if ($existingCount + $incomingCount > self::MAX_PHOTOS) {
            $remaining = max(0, self::MAX_PHOTOS - $existingCount);
            $this->errorMessage = "Cannot upload {$incomingCount} photo(s). Maximum gallery limit is " . self::MAX_PHOTOS . " photos. You currently have {$existingCount} photo(s) and can add at most {$remaining} more.";
            return;
        }

        $nextOrder = (int) ($user->profilePhotos()->max('sort_order') ?? 0) + 1;
        $hasPrimary = $user->profilePhotos()->where('is_primary', true)->exists();

        foreach ($this->newPhotos as $file) {
            $path = $file->store('profile-photos', 'public');
            $isPrimary = ! $hasPrimary;

            $user->profilePhotos()->create([
                'path' => $path,
                'is_primary' => $isPrimary,
                'sort_order' => $nextOrder++,
            ]);

            if ($isPrimary) {
                $hasPrimary = true;
            }
        }

        $user->syncPrimaryPhotoToMemberProfile();

        $this->newPhotos = [];
        $this->successMessage = 'Photos uploaded successfully!';
    }

    public function setPrimary(int $photoId): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $user = Auth::user();
        $photo = ProfilePhoto::where('id', $photoId)->where('user_id', $user->id)->firstOrFail();

        $user->profilePhotos()->update(['is_primary' => false]);
        $photo->update(['is_primary' => true]);

        $user->syncPrimaryPhotoToMemberProfile();

        $this->successMessage = 'Primary profile photo updated successfully!';
    }

    public function moveUp(int $photoId): void
    {
        $user = Auth::user();
        $photos = $user->profilePhotos()->get();
        $index = $photos->search(fn ($p) => $p->id === $photoId);

        if ($index !== false && $index > 0) {
            $prev = $photos[$index - 1];
            $current = $photos[$index];

            $temp = $prev->sort_order;
            $prev->update(['sort_order' => $current->sort_order]);
            $current->update(['sort_order' => $temp]);
        }
    }

    public function moveDown(int $photoId): void
    {
        $user = Auth::user();
        $photos = $user->profilePhotos()->get();
        $index = $photos->search(fn ($p) => $p->id === $photoId);

        if ($index !== false && $index < $photos->count() - 1) {
            $next = $photos[$index + 1];
            $current = $photos[$index];

            $temp = $next->sort_order;
            $next->update(['sort_order' => $current->sort_order]);
            $current->update(['sort_order' => $temp]);
        }
    }

    public function deletePhoto(int $photoId): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $user = Auth::user();
        $photo = ProfilePhoto::where('id', $photoId)->where('user_id', $user->id)->firstOrFail();

        $wasPrimary = $photo->is_primary;

        if ($photo->path && Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        // Reindex sort_order
        $remaining = $user->profilePhotos()->get();
        foreach ($remaining as $idx => $p) {
            $p->update(['sort_order' => $idx + 1]);
        }

        if ($wasPrimary) {
            $first = $user->profilePhotos()->first();
            if ($first) {
                $user->profilePhotos()->update(['is_primary' => false]);
                $first->update(['is_primary' => true]);
            }
        }

        $user->syncPrimaryPhotoToMemberProfile();

        $this->successMessage = 'Photo deleted successfully.';
    }

    public function render()
    {
        $user = Auth::user();
        $photos = $user ? $user->profilePhotos : collect();
        $currentCount = $photos->count();
        $maxPhotos = self::MAX_PHOTOS;

        return view('livewire.member.profile.photos', [
            'photos' => $photos,
            'currentCount' => $currentCount,
            'maxPhotos' => $maxPhotos,
        ])->layout('components.layouts.app', ['title' => 'Manage Profile Photos']);
    }
}
