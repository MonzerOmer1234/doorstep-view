<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title' ,
        'description' ,
        'price',
        'address' ,
        'user_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function amenities(){
        return $this->belongsToMany(Amenity::class);
    }
}
