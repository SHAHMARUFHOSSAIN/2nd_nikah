<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('country_scope', 20); // 'BD' or 'INTERNATIONAL'
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10); // 'BDT' or 'USD'
            $table->string('billing_interval', 20); // 'weekly' or 'monthly'
            $table->integer('duration_days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the 4 official business plans
        DB::table('membership_plans')->insert([
            [
                'name' => 'Weekly',
                'slug' => 'weekly-bdt',
                'description' => '7-day premium matrimonial access for members in Bangladesh.',
                'country_scope' => 'BD',
                'amount' => 100.00,
                'currency' => 'BDT',
                'billing_interval' => 'weekly',
                'duration_days' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monthly',
                'slug' => 'monthly-bdt',
                'description' => '30-day premium matrimonial access for members in Bangladesh.',
                'country_scope' => 'BD',
                'amount' => 300.00,
                'currency' => 'BDT',
                'billing_interval' => 'monthly',
                'duration_days' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Weekly',
                'slug' => 'weekly-usd',
                'description' => '7-day premium matrimonial access for international members.',
                'country_scope' => 'INTERNATIONAL',
                'amount' => 5.00,
                'currency' => 'USD',
                'billing_interval' => 'weekly',
                'duration_days' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monthly',
                'slug' => 'monthly-usd',
                'description' => '30-day premium matrimonial access for international members.',
                'country_scope' => 'INTERNATIONAL',
                'amount' => 10.00,
                'currency' => 'USD',
                'billing_interval' => 'monthly',
                'duration_days' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
