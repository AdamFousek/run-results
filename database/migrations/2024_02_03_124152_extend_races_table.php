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
        Schema::table('races', function (Blueprint $table) {
            $table->integer('vintage')->nullable()->after('time');
            $table->string('region')->default('')->after('location');
            $table->float('latitude')->nullable()->after('region');
            $table->float('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->dropColumn('vintage');
            $table->dropColumn('region');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
