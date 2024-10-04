<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'agent_id', // Foreign key for the agent
        'title',
        'description',
        'price',
        'location',
        'bedrooms',
        'bathrooms',
        'area',
        'property_type',
        'status',
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

}
