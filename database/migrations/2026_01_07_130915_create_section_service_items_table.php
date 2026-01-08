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
        Schema::create('section_service_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('details');
            $table->string('button');
            $table->foreignId('section_service_id')->on('section_services')->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_service_items');
    }
};
