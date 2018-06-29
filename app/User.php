<?php

namespace App;

use App\Entities\Library\Book;
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

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_code', 'personal_photo', 'role', 'is_writer',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_writer' => 'boolean',
    ];

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_code' => Str::uuid(),
            'status' => self::STATUS_WAIT,
            'role' => self::ROLE_USER,
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

    public function changeRole($role): void
    {
        if (!\in_array($role, self::getRoles(), true)) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }
        $this->update(['role' => $role]);
    }

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isWriter(): bool
    {
        return $this->is_writer;
    }

    public function becomeWriter()
    {
        $this->update(
            ['is_writer' => true]
        );
    }

    public function becomeNotWriter()
    {
        $this->update(
            ['is_writer' => false]
        );
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'user_id', 'id');
    }

    public function bundles()
    {
        return $this->hasMany(Book\Bundle::class, 'user_id', 'id');
    }

    public function subscribe(int $id)
    {
        $this->defendWriter();
        if ($this->hasInSubscribers($id)) {
            throw new \DomainException('This user is already signed up');
        }
        $this->subscribers()->attach($id);
    }

    public function unsubscribe(int $id)
    {
        $this->defendWriter();
        $this->subscribers()->detach($id);
    }

    public function hasInSubscribers(int $id)
    {
        $this->defendWriter();
        return $this->subscribers()->where('id', $id)->exists();
    }

    public function subscribers()
    {
        $this->defendWriter();
        return $this->belongsToMany(self::class, 'writers_subscribers', 'writer_id', 'subscriber_id');
    }

    public function defendWriter()
    {
        if (!$this->isWriter()) {
            throw new \DomainException('This user is not writer');
        }
    }
}
