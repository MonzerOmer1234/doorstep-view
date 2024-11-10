<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;

class Agent extends Model
{
    use HasFactory , Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'profile_picture',
    ];
    public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPassword($token));
}

}

