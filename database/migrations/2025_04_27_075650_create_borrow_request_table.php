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
        Schema::create('borrow_request', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('diwan_num')->unique();
            $table->date('diwan_date');
            $table->text('request_goal')->nullable();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->enum('request_type',['external','enternal']);
            // [ItemRecievedFromStudent,ItemDeliverdeToStudent,PartialDelivery,NoDelivered]
            $table->enum('request_state',['IRFS','IDTS','PD','ND']);
            $table->date('request_deliver_date')->nullable();
            $table->text('request_deliver_note')->nullable();
            $table->date('request_receive_date')->nullable();
            $table->text('request_receive_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_request');
    }
};
