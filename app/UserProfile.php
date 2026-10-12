<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
