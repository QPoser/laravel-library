<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 08.08.18
 * Time: 21:19
 */

namespace App\Services\Library;


use App\Entities\Library\Book;
use App\Entities\Library\Book\Appeal;
use App\User;
use DB;

class AppealService
{

    public function create(string $reason, User $user, Book $book): Appeal
    {
        return DB::transaction(function () use ($reason, $user, $book) {

            /** @var Appeal $appeal */
            $appeal = Appeal::make([
                'reason' => $reason,
                'status' => Appeal::STATUS_WAIT,
            ]);

            $appeal->user()->associate($user);

            $appeal->book()->associate($book);

            $appeal->saveOrFail();

            return $appeal;

        });
    }

    public function accept(Appeal $appeal)
    {
        $appeal->accept();
    }

    public function cancel(Appeal $appeal)
    {
        $appeal->cancel();
    }

}