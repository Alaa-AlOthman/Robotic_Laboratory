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
        Schema::create('broken_pieces', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('piece_id')->references('id')->on('pieces')->onDelete('cascade')->unique();
            $table->integer('broken_piece_num')->nullable();
            $table->integer('fixed_piece_num')->nullable();
            $table->text('broken_piece_note')->nullable();
            $table->json('borrow_diwan_num')->nullable();
            $table->boolean('isFixed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broken_pieces');
    }
};
