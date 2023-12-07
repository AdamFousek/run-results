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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('runner_id');
            $table->foreign('runner_id')->references('id')
                ->on('runners')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('race_id');
            $table->foreign('race_id')->references('id')
                ->on('races')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('position');
            $table->unsignedBigInteger('time');
            $table->string('category');
            $table->unsignedBigInteger('category_position');
            $table->boolean('DNF')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
