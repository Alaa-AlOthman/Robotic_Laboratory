<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compounds extends Model
{
    //
    protected $table = 'compounds';
    protected $fillable = ['diwan_num', 'diwan_date', 'compound_name', 'borrow_id'];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class,'borrow_id');
    }

    public function CompoundPieces()
    {
        return $this->hasMany(CompoundPieces::class,'compound_id');
    }

        public function pieces()
    {
        return $this->belongsToMany(Piece::class, 'compound_pieces')->withPivot('pieces_num');
    }
}
