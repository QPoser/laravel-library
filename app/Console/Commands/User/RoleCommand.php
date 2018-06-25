<?php

namespace App\Console\Commands\User;

use App\User;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Set role for user';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }

        try {
            $user->changeRole($role);
            $this->info('Role is successfully changed');
            return true;
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return false;
        }
    }
}
