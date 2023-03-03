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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('address');
            $table->string('landmark');
            $table->string('city');
            $table->string('state');
            $table->string('pin_code');
            $table->string('email_id');
            $table->string('contact_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
