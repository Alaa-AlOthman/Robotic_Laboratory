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
        Schema::create('compounds', function (Blueprint $table) {
            $table->id();
            $table->string('diwan_num')->unique();
            $table->date('diwan_date');
            $table->string('compound_name')->unique();
            $table->foreignId('borrow_id')->references('id')->on('borrow_request')->onDelete('cascade')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compound');
    }
};
