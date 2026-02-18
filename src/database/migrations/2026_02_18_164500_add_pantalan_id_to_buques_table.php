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
        Schema::table('buques', function (Blueprint $table) {
            $table->foreignId('pantalan_id')->nullable()->after('muelle_id')->constrained('pantalans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buques', function (Blueprint $table) {
            $table->dropForeign(['pantalan_id']);
            $table->dropColumn('pantalan_id');
        });
    }
};
