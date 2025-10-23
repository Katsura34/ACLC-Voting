<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('max_winners')->default(1); // e.g., Senators can be >1
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
            $table->unique(['election_id','name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('positions');
    }
};