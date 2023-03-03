<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("role")->after("phone_number");
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string("reseller_price")->after("offer_price");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                "role"
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                "reseller_price"
            ]);
        });
    }
};
