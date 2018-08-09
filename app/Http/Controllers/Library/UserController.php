<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 29.06.18
 * Time: 20:56
 */

namespace App\Http\Controllers\Library;


use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UserController extends Controller
{

    public function show(User $user)
    {
        $currentUser = Auth::user();
        $isCurrent = $currentUser->id === $user->id;

        return view('library.user.show', compact('user', 'currentUser', 'isCurrent'));
    }

    public function subscribe(User $writer, User $subscriber)
    {
        try {
            $writer->subscribe($subscriber);
        } catch (\DomainException $e) {
            return redirect()->route('library.users.show', $writer)->with('error', $e->getMessage());
        }

        return redirect()->route('library.users.show', $writer)->with('success', 'You successfully subscribed');
    }

    public function unsubscribe(User $writer, User $subscriber)
    {
        try {
            $writer->unsubscribe($subscriber);
        } catch (\DomainException $e) {
            return redirect()->route('library.users.show', $writer)->with('error', $e->getMessage());
        }

        return redirect()->route('library.users.show', $writer)->with('success', 'You successfully unsubscribed');
    }

}