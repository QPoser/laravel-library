<?php

namespace App\Providers;

use App\Entities\Library\Book;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-own-book', function (User $user, Book $book) {
            return $book->user_id === $user->id;
        });
    }
}
