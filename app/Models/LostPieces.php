<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostPieces extends Model
{
    protected $fillable = ['piece_id', 'requested_piece_num', 'lost_piece_note', 'borrow_diwan_num'];

    protected $casts = [
        'borrow_diwan_num' => 'array',  // Automatically cast to array
    ];

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }
}
