<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendees(){
        return $this->hasMany(Attendee::class);
    }
    protected $fillable = [
        "name" , 
        "start_time" , 
        "description" , 
        "end_time"   , 
        "user_id"
    ];
}
