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
        if (!Schema::hasTable('coach_plans')) {
            Schema::create('coach_plans', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('amount')->nullable();
                $table->longText('description')->nullable();
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
        Schema::dropIfExists('coach_plans');
    }
};
