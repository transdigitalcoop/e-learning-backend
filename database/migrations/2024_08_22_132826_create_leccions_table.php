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
        Schema::create('leccions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_modulo')->constrained('modulos')->onDelete('cascade');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('video');
            $table->integer('orden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leccions');
    }
};