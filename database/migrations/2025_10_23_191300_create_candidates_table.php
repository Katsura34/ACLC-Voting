<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->cascadeOnDelete();
            $table->foreignId('party_id')->constrained('parties')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('positions')->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('course')->nullable();
            $table->string('year_level')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
            $table->index(['election_id', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
