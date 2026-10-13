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
        Schema::create('borrow_request_piece', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->unsignedBigInteger('borrow_request_id');
            // $table->unsignedBigInteger('piece_id');
         
            $table->foreignId('borrow_request_id')->references('id')->on('borrow_request')->onDelete('cascade');
            $table->foreignId('piece_id')->references('id')->on('pieces')->onDelete('cascade');
            $table->unique(['borrow_request_id','piece_id']);
            $table->integer('requested_pieces_num')->default(0);
            $table->integer('delivered_pieces_num')->default(0);
             // [DeliveredAll,PartialDelivery,NoDelivered,Broken]
             $table->enum('piece_deliver_state',['DA','PD','ND']);
             $table->text('piece_deliver_note')->nullable();
            // [RecievedAll,PartialRecieve,NoReceived,Broken]
             $table->enum('piece_recieve_state',['RA','PR','NR','BR']);
            //  $table->integer('piece_recieve_num')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_request_piece');
    }
};
