<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $table = 'borrow_request';
    protected $fillable = ['diwan_num', 'diwan_date', 'request_goal', 'team_id', 'request_type', 'request_state', 'request_deliver_date', 'request_deliver_note', 'request_receive_date', 'request_receive_note'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function BorrowRequestPieces()
    {
        return $this->hasMany(BorrowRequestPiece::class);
    }

    public function pieces()
    {
        return $this->belongsToMany(Piece::class, 'borrow_request_piece')->withPivot('requested_pieces_num', 'delivered_pieces_num', 'piece_deliver_state', 'piece_deliver_note', 'piece_recieve_state');
    }

    public function Compound()
    {
        return $this->hasOne(Compounds::class);
    }
}
