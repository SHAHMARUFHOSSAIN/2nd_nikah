<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',
        'body',
        'attachment_path',
        'attachment_mime',
        'attachment_original_name',
        'read_at',
        'deleted_at',
        'reply_to_message_id',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to_message_id');
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null || $this->type === 'deleted';
    }

    public function isImage(): bool
    {
        return $this->type === 'image' && ! empty($this->attachment_path);
    }

    public function isWhatsAppRequest(): bool
    {
        return $this->type === 'whatsapp_request';
    }

    public function isReply(): bool
    {
        return $this->reply_to_message_id !== null;
    }
}
