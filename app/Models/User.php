<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

enum UserType {
    case STOCKIST;
    case ADMIN;
    case DISTRIBUTOR;
}

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        "role"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public static function nextId() {
        $totalUsers = User::count();

        if ($totalUsers === 0) {
            return "DFL" . 1000000;
        }

        return "DFL" . 1000000 + $totalUsers;
    }

    public function upline() {
        return $this->hasOne(Upline::class);
    }

    public function distributor() {
        return $this->hasOne(Distributor::class);
    }

    public function stockist() {
        return $this->hasOne(Stockist::class);
    }
}
