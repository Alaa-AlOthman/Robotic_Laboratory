<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compound_pieces', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('compound_id')->references('id')->on('compounds')->onDelete('cascade');
            $table->foreignId('piece_id')->references('id')->on('pieces')->onDelete('cascade');
            //
            $table->integer('pieces_num')->default(0);
             $table->unique(['compound_id', 'piece_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compound_pieces');
    }
};
