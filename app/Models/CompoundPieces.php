<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompoundPieces extends Model
{
    //
    protected $table = 'compound_pieces';
    protected $fillable = ['compound_id','piece_id','pieces_num'];

    public function piece()
    {
        return $this->belongsTo(Piece::class, 'piece_id');
    }

    public function Compound()
    {
        return $this->belongsTo(Compounds::class,'compound_id');
    }
}
