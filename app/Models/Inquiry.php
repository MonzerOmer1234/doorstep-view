<?php
    
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = ['property_id', 'name', 'email', 'phone', 'message'];

    // Define the relationship with Property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
