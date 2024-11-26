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
        if (!Schema::hasTable('venue_details')) {
            Schema::create('venue_details', function (Blueprint $table) {
                $table->id();
                $table->integer('venue_id')->nullable();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->string('image')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_details');
    }
};
