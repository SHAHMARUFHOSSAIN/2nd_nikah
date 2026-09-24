<?php

namespace App\Livewire\Member;

use App\Models\MemberProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public ?string $first_name = '';
    public ?string $last_name = '';
    public ?string $date_of_birth = '';
    public ?string $gender = '';
    public ?string $marital_status = '';
    public ?string $religion = '';
    public ?string $location = '';
    public ?string $city = '';
    public ?string $country = '';
    public ?string $phone = '';

    public ?int $height = null;
    public ?string $education = '';
    public ?string $occupation = '';
    public ?string $about_me = '';
    public int $children_count = 0;

    public bool $is_profile_visible = true;
    public $photo;

    protected function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'marital_status' => ['required', 'in:Never Married,Divorced,Widowed,Single Parent'],
            'religion' => ['required', 'in:Islam,Hinduism,Christianity,Buddhism,Other'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'height' => ['nullable', 'integer', 'min:50', 'max:250'],
            'education' => ['nullable', 'string', 'max:255'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'about_me' => ['nullable', 'string', 'max:2000'],
            'children_count' => ['nullable', 'integer', 'min:0', 'max:20'],
            'is_profile_visible' => ['boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();

        // Retrieve or create empty profile for the authenticated user
        $profile = MemberProfile::firstOrCreate(['user_id' => $user->id]);

        $this->first_name = $profile->first_name ?? '';
        $this->last_name = $profile->last_name ?? '';
        $this->date_of_birth = $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '';
        $this->gender = $profile->gender ?? '';
        $this->marital_status = $profile->marital_status ?? '';
        $this->religion = $profile->religion ?? '';
        $this->location = $profile->location ?? '';
        $this->city = $profile->city ?? '';
        $this->country = $profile->country ?? '';
        $this->phone = $profile->phone ?? '';

        $this->height = $profile->height;
        $this->education = $profile->education ?? '';
        $this->occupation = $profile->occupation ?? '';
        $this->about_me = $profile->about_me ?? '';
        $this->children_count = $profile->children_count ?? 0;
        $this->is_profile_visible = (bool) ($profile->is_profile_visible ?? true);
    }

    public function saveProfile()
    {
        $this->validate();

        $user = Auth::user();
        $profile = MemberProfile::where('user_id', $user->id)->firstOrFail();

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'religion' => $this->religion,
            'location' => $this->location,
            'city' => $this->city,
            'country' => $this->country,
            'phone' => $this->phone,
            'height' => $this->height,
            'education' => $this->education,
            'occupation' => $this->occupation,
            'about_me' => $this->about_me,
            'children_count' => $this->children_count,
            'is_profile_visible' => $this->is_profile_visible,
        ];

        if ($this->photo) {
            if ($profile->profile_photo_path && Storage::disk('public')->exists($profile->profile_photo_path)) {
                Storage::disk('public')->delete($profile->profile_photo_path);
            }

            $path = $this->photo->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $profile->update($data);
        $profile->syncCompletionStatus();

        session()->flash('status', 'Your member profile has been updated successfully!');
        $this->photo = null;
    }

    public function removePhoto()
    {
        $user = Auth::user();
        $profile = MemberProfile::where('user_id', $user->id)->firstOrFail();

        if ($profile->profile_photo_path && Storage::disk('public')->exists($profile->profile_photo_path)) {
            Storage::disk('public')->delete($profile->profile_photo_path);
        }

        $profile->update(['profile_photo_path' => null]);
        $profile->syncCompletionStatus();

        session()->flash('status', 'Profile photo removed successfully.');
    }

    public function render()
    {
        $user = Auth::user();
        $profile = MemberProfile::where('user_id', $user->id)->first();
        $completionPercentage = $profile ? $profile->calculateCompletionPercentage() : 0;

        return view('livewire.member.profile', [
            'profile' => $profile,
            'completionPercentage' => $completionPercentage,
        ])->layout('components.layouts.app', ['title' => 'My Member Profile']);
    }
}
