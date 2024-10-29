<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' ,
        'icon',
        'category',
         'address',
         'latitude',
         'longitude'
    ];

    public function properties(){
        return $this->belongsToMany(Property::class , 'property_amenities');
    }



}
