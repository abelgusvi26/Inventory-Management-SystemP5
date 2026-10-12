<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password_hash', 'role', 'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password_hash', 'remember_token',
    ];

    /**
     * Override Auth password column to match database schema (password_hash)
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Relasi 1-to-1 dengan user_profiles
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    /**
     * Relasi 1-to-many dengan stock_movements
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'user_id', 'id');
    }

    /**
     * Helper cek peran (Admin / Staff)
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }
}
