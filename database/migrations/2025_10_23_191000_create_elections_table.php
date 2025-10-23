<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(false);
            $table->enum('status', ['draft','active','completed','cancelled'])->default('draft');
            // Analytics
            $table->unsignedInteger('total_registered_voters')->default(0);
            $table->unsignedInteger('total_votes_cast')->default(0);
            $table->decimal('voting_percentage',5,2)->default(0.00);
            $table->boolean('results_published')->default(false);
            $table->timestamp('results_published_at')->nullable();
            // Options
            $table->boolean('allow_abstain')->default(false);
            $table->boolean('show_live_results')->default(false);
            $table->timestamps();
            $table->index(['is_active','status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('elections');
    }
};