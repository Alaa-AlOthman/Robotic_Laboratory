<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrokenPieces extends Model
{
    protected $fillable = ['piece_id', 'broken_piece_num', 'fixed_piece_num', 'broken_piece_note', 'borrow_diwan_num', 'isFixed'];

    protected $casts = [
        'borrow_diwan_num' => 'array',  // Automatically cast to array
    ];

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }
}
