<?php

namespace App;

use App\Entities\Library\Book;
use App\Entities\Library\Book\Appeal;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

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

    use Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password', 'status', 'verify_code', 'personal_photo', 'role', 'is_writer',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'is_writer' => 'boolean',
    ];

    public static function register(string $name, string $email, string $password, bool $isWriter = false): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'verify_code' => Str::uuid(),
            'status' => self::STATUS_WAIT,
            'role' => self::ROLE_USER,
            'is_writer' => $isWriter,
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
        if (!\in_array($role, self::rolesList(), true)) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new \DomainException('Role is already assigned.');
        }
        $this->update(['role' => $role]);
    }

    public static function rolesList()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }

    public static function statusesList()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_WAIT,
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
        if ($this->isWriter()) {
            throw new \DomainException('You are already a writer');
        }

        $this->update(
            ['is_writer' => true]
        );
    }

    public function becomeNotWriter()
    {
        if (!$this->isWriter()) {
            throw new \DomainException('You are not a writer');
        }

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

    public function appeals()
    {
        return $this->hasMany(Appeal::class, 'user_id', 'id');
    }

    public function subscribe(User $user)
    {
        $this->defendWriter();
        if ($this->hasInSubscribers($user->id)) {
            throw new \DomainException('This user is already signed up');
        }
        $this->subscribers()->attach($user->id);
    }

    public function unsubscribe(User $user)
    {
        $this->defendWriter();
        $this->subscribers()->detach($user->id);
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
