<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_share_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->string('status', 20)->default('pending'); // pending, accepted, rejected, cancelled
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->string('type', 30)->default('text')->after('sender_id'); // text, image, whatsapp_request, deleted, reply
            $table->string('attachment_path')->nullable()->after('body');
            $table->string('attachment_mime')->nullable()->after('attachment_path');
            $table->string('attachment_original_name')->nullable()->after('attachment_mime');
            $table->timestamp('deleted_at')->nullable()->after('read_at');
            $table->foreignId('reply_to_message_id')->nullable()->after('deleted_at')->constrained('messages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_message_id']);
            $table->dropColumn([
                'type',
                'attachment_path',
                'attachment_mime',
                'attachment_original_name',
                'deleted_at',
                'reply_to_message_id',
            ]);
        });

        Schema::dropIfExists('whatsapp_share_requests');
    }
};
