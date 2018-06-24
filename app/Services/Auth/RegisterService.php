<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 24.06.18
 * Time: 16:30
 */

namespace App\Services\Auth;


use App\Mail\VerifyMail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;

class RegisterService
{

    private $dispatcher;
    private $mailer;

    public function __construct(Mailer $mailer, Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->mailer = $mailer;
    }

    public function register(Request $request): void
    {
        $user = User::register(
            $request['name'],
            $request['email'],
            $request['password']
        );

        $this->mailer->to($user->email)->send(new VerifyMail($user));
        $this->dispatcher->dispatch(new Registered($user));
    }

    public function verify($id): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $user->verify();

        $this->mailer->to($user->email)->send(new VerifyMail($user));
    }

}