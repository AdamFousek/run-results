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
        Schema::create('upload_file_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('race_id');
            $table->foreign('race_id')->references('id')
                ->on('races')->onUpdate('cascade')->onDelete('cascade');
            $table->string('file_path');
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }
};
