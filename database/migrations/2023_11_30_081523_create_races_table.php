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
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')
                ->on('races')->onUpdate('cascade')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->string('name');
            $table->dateTime('date')->nullable();
            $table->string('location')->nullable();
            $table->float('distance')->nullable();
            $table->string('surface')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_parent')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};
