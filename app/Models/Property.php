<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'agent_id',
        'user_id', // Foreign key for the agent
        'title',
        'description',
        'price',
        'location',
        'bedrooms',
        'bathrooms',
        'area',
        'property_type',
        'status',
        'view_count',
    ];

    // Define relationship with the Agent model
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    // app/Models/Property.php

public function favorites()
{
    return $this->hasMany(Favorite::class);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class , 'property_amenities');
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

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }
    public function getTotalFeedback()
    {
        return $this->feedbacks()->count();
    }
    public function visitRequests()
    {
        return $this->hasMany(VisitRequest::class);
    }
    public function getTotalVisitRequests()
    {
        return $this->visitRequests()->count();
    }



}
