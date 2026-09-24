<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            // Basic Information
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();

            // Matrimonial Information
            $table->unsignedSmallInteger('height')->nullable()->comment('Height in cm');
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->text('about_me')->nullable();
            $table->unsignedTinyInteger('children_count')->default(0);

            // Profile Status & Media
            $table->string('profile_photo_path')->nullable();
            $table->boolean('is_profile_complete')->default(false);
            $table->boolean('is_profile_visible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
