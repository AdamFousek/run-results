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
        Schema::create('upload_file_result_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_file_result_id');
            $table->foreign('upload_file_result_id')->references('id')
                ->on('upload_file_results')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('row_number');
            $table->string('data');
            $table->string('error');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_file_result_rows');
    }
};
