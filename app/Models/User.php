<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

 public function apartments(){
    return $this->hasMany(Apartment::class);
 }

 public function feedbacks(){
    return $this->hasMany(Feedback::class);
 }
 // app/Models/User.php

public function favorites()
{
    return $this->hasMany(Favorite::class);
}
 public function isAdmin()
    {
        return $this->is_admin;
    }
     // User has many messages (as the sender)
     public function messages()
     {
         return $this->hasMany(Message::class);
     }

     // User can participate in many threads
     public function threads()
     {
         return $this->belongsToMany(Thread::class, 'messages');
     }
     public function orders()
    {
    return $this->hasMany(Order::class);
    }

}




