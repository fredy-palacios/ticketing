<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use phpseclib3\System\SSH\Agent;

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
        return DB::table('users')->where('role', self::ROLE_AGENT)->pluck('id')->toArray();
    }

    //get all agents
    public static function getAllAgents(): array
    {
        return DB::table('users')->where('role', self::ROLE_AGENT)->get()->all();
    }

    //get agent
    public static function getAgentById(int $id): object
    {
        return DB::table('users')->where('role', self::ROLE_AGENT)->where('id', $id)->first();
    }

    //get all users
    public static function getAllUsers(): array
    {
        return DB::table('users')->where('role', self::ROLE_USER)->get()->all();
    }

    //get user
    public static function getUserById(int $id): object
    {
        return DB::table('users')->where('role', self::ROLE_USER)->where('id', $id)->first();
    }
}
