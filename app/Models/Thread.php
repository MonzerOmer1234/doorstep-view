<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = ['subject'];

    // A thread has many messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // A thread can belong to multiple users (via messages)
    public function participants()
    {
        return $this->belongsToMany(User::class, 'messages');
    }
}
