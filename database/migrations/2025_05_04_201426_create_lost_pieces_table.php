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
        Schema::create('lost_pieces', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->unsignedBigInteger('piece_id');
            $table->foreignId('piece_id')->references('id')->on('pieces')->onDelete('cascade')->unique();
            $table->integer('requested_piece_num')->nullable();
            $table->text('lost_piece_note')->nullable();
            $table->json('borrow_diwan_num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_pieces');
    }
};
