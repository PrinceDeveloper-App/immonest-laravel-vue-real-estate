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
        Schema::table('listings', function (Blueprint $table) {
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->unsignedSmallInteger('area')->nullable();

            $table->tinyText('city')->nullable();
            $table->tinyText('code')->nullable();
            $table->tinyText('street')->nullable();
            $table->tinyText('street_number')->nullable();

            $table->unsignedBigInteger('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['bedrooms', 'bathrooms', 'area', 'city', 'code', 'street', 'street_number', 'price']);
        });
    }
};
