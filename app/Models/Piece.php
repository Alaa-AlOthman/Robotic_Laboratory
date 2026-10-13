<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Piece extends Model
{
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::deleting(function($model){
    //         if($model->image){
    //             Storage::delete('public/images/'.$model->image);
    //         }
    //     });
    // }
    protected $fillable = ['piece_name', 'Base_num', 'available_num', 'description', 'image', 'code', 'piece_type'];

    public function borrowRequests()
    {
        return $this->belongsToMany(BorrowRequest::class, 'borrow_request_piece')->withPivot('requested_pieces_num', 'delivered_pieces_num', 'piece_deliver_state', 'piece_deliver_note', 'piece_recieve_state');
    }

    public function lost()
    {
        return $this->hasOne(LostPieces::class);
    }

    public function broken()
    {
        return $this->hasOne(BrokenPieces::class);
    }

    public function Compounds()
    {
        return $this->belongsToMany(Compounds::class, 'compound_pieces')->withPivot('pieces_num');
    }
}
