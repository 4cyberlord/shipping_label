<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // General Settings
            $table->string('app_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('default_language')->default('en');
            $table->string('timezone')->default('Africa/Accra');
            $table->string('currency')->default('GHS');

            // Notification Settings
            $table->boolean('email_notifications_enabled')->default(true);
            $table->boolean('sms_notifications_enabled')->default(false);
            $table->string('default_sms_sender')->nullable();
            $table->string('default_email_sender')->nullable();

            // Delivery Settings
            $table->integer('default_delivery_time')->nullable();
            $table->decimal('maximum_parcel_weight', 8, 2)->nullable();
            $table->json('allowed_delivery_areas')->nullable();

            // Payment Settings
            $table->string('payment_gateway')->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('refund_policy_link')->nullable();

            // Twilio Settings
            $table->string('twilio_account_sid')->nullable();
            $table->string('twilio_auth_token')->nullable();
            $table->string('twilio_phone_number')->nullable();
            $table->string('messaging_service_sid')->nullable();

            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            'app_name' => config('app.name'),
            'default_language' => 'en',
            'timezone' => 'Africa/Accra',
            'currency' => 'GHS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
