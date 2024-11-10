<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ResetPasswordNotification;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'profile_picture',
    ];
    public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}

}

