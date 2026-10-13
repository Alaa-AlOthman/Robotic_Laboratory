<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class borrowRequestPiece extends Model
{
    protected $table = 'borrow_request_piece';
    protected $fillable = ['borrow_request_id','piece_id','requested_pieces_num', 'delivered_pieces_num', 'piece_deliver_state', 'piece_deliver_note', 'piece_recieve_state', 'piece_recieve_num'];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function piece()
    {
        return $this->belongsTo(Piece::class, 'piece_id');
    }
}
