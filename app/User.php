<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * Class User
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $status
 */
class User extends Authenticatable
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_code',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_code' => Str::uuid(),
            'status' => self::STATUS_WAIT,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already verified.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }
}
