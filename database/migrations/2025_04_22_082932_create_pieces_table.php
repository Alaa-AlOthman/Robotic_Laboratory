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
        Schema::create('pieces', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('piece_name');
            $table->integer('Base_num');
            $table->integer('available_num')->nullable();
            $table->text('description')->nullable();
            $table->string('image');
            $table->integer('code')->default(0);
            $table->string('piece_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces');
    }
};
