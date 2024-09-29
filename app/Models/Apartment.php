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
        return $this->belongsToMany(Amenity::class , 'apartment_amenities');
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
    public function addAmenity($amenityId)
    {
           // Check if the amenity exists
           if (!Amenity::find($amenityId)) {
            throw new \Exception("Amenity with ID {$amenityId} does not exist.");
        }
        if ($this->amenities()->where('amenity_id', $amenityId)->exists()) {
            // attach the amenity

            $this->amenities()->syncWithoutDetaching($amenityId);
        }


        return $this;
    }
    public function detachAmenity($amenityId)
    {
        // Check if the amenity exists
        if (!Amenity::find($amenityId)) {
            throw new \Exception("Amenity with ID {$amenityId} does not exist.");
        }

        // Check if the amenity is attached to the apartment
        if ($this->amenities()->where('amenity_id', $amenityId)->exists()) {
            // Detach the amenity
            $this->amenities()->detach($amenityId);
        }

        return $this;
    }
}
