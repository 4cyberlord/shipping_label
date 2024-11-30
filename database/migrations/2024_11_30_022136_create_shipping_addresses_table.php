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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['from', 'to']);
            $table->string('company_name')->nullable();
            $table->string('contact_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('country')->default('Ghana');
            $table->text('delivery_instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('from_address_id')->constrained('shipping_addresses');
            $table->foreignId('to_address_id')->constrained('shipping_addresses');
            $table->decimal('weight', 8, 2);
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->enum('status', [
                'pending',
                'processing',
                'in_transit',
                'delivered',
                'failed',
                'cancelled'
            ])->default('pending');
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->json('label_data')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
        Schema::dropIfExists('shipping_addresses');
    }
};
