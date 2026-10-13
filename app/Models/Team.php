<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['team_name', 'team_members', 'teams_goal', 'team_leader'];

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }
  public function user(){
    return $this->belongsTo(User::class,'id');
  }
}
