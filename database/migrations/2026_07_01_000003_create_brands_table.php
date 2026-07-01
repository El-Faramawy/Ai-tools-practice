<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 255)->nullable(false);
            $table->unsignedBigInteger('country_id')->nullable(false);
            $table->foreign('country_id')
                  ->references('id')
                  ->on('countries')
                  ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            // Enforce unique brand name within a country (excludes soft-deleted via app layer)
            $table->unique(['name', 'country_id']);
            // Speed up country-scoped listing queries
            $table->index('country_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
