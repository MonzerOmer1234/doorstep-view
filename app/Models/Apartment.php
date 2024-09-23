<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'address', 'available', 'rooms', 'area', 'building_age'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    // Custom query scopes
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }

    public function scopeByPrice($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeByRooms($query, $rooms)
    {
        return $query->where('rooms', $rooms);
    }

    public function scopeByArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function scopeByBuildingAge($query, $age)
    {
        return $query->where('building_age', '<=', $age);
    }
}
