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
        Schema::table('borrow_request_piece', function (Blueprint $table) {
            //
             $table->integer('piece_recieve_num')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_request_piece', function (Blueprint $table) {
            //
            $table->dropColumn('piece_recieve_num');
        });
    }
};
