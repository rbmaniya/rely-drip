<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->string('country')->nullable();
            $table->string('subject')->nullable();
            $table->string('source')->default('general');
            $table->string('instagram_handle')->nullable();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('custom_order_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('piece_type');
            $table->string('stone_preference')->nullable();
            $table->string('metal_preference')->nullable();
            $table->string('engraving')->nullable();
            $table->decimal('estimated_price', 12, 2)->nullable();
            $table->string('name');
            $table->string('whatsapp');
            $table->string('email');
            $table->string('country')->nullable();
            $table->text('vision');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_order_requests');
        Schema::dropIfExists('contact_messages');
    }
};
