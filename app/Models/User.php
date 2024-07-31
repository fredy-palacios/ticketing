<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_AGENT = 'agent';
    const ROLE_USER = 'user';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
    private string $role;

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

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    //get agent by id
    public static function getAgentsId(): array
    {
        return self::where('role', self::ROLE_AGENT)->get()->pluck('id')->all();
    }

    //get all agents
    public static function getAllAgents(): ?array
    {
        return self::where('role', self::ROLE_AGENT)->get()->all();
    }

    //get agent
    public static function getAgentById(int $id): ?User
    {
        return self::where('role', self::ROLE_AGENT)->find($id);
    }

    //get all users
    public static function getAllUsers(): ?array
    {
        return self::where('role', self::ROLE_USER)->get()->all();
    }

    //get user
    public static function getUserById(int $id): ?User
    {
        return self::where('role', self::ROLE_USER)->find($id);
    }
}
