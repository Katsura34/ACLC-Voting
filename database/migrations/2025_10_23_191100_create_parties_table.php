<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['election_id','name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('parties');
    }
};