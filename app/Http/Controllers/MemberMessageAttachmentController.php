<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MemberMessageAttachmentController extends Controller
{
    /**
     * Securely stream private chat image attachments.
     */
    public function show(Request $request, Message $message)
    {
        $user = Auth::user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        if (! $user->hasVerifiedEmail() || ! $user->is_active) {
            abort(403, 'Forbidden access.');
        }

        // Must be a conversation participant
        if (! $message->conversation || ! $message->conversation->isParticipant($user->id)) {
            abort(403, 'Unauthorized access to chat attachment.');
        }

        // Must not be soft-deleted
        if ($message->isDeleted()) {
            abort(404, 'Attachment no longer available.');
        }

        // Block status check
        $partner = $message->conversation->getPartnerUser($user->id);
        if ($partner && $user->hasBlockedOrIsBlockedBy($partner->id)) {
            abort(403, 'Access denied due to block restrictions.');
        }

        if (empty($message->attachment_path) || ! Storage::disk('local')->exists($message->attachment_path)) {
            abort(404, 'Attachment file not found.');
        }

        $fullPath = Storage::disk('local')->path($message->attachment_path);
        
        $mimeType = $message->attachment_mime ?: mime_content_type($fullPath) ?: 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, no-transform, no-store, must-revalidate',
        ]);
    }
}
