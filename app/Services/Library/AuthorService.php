<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 08.08.18
 * Time: 21:19
 */

namespace App\Services\Library;

use App\Entities\Library\Book\Appeal;
use App\Entities\Library\Book\Author;
use DB;

class AuthorService
{

    public function create(string $name, $active = false): Author
    {
        return DB::transaction(function () use ($name, $active) {

            /** @var Author $author */
            $author = Author::make([
                'name' => $name,
                'status' => $active ? Author::STATUS_ACTIVE : Author::STATUS_WAIT,
            ]);

            $author->saveOrFail();

            return $author;

        });
    }

    public function update(Author $author, array $params): Author
    {
        $author->update($params);

        return $author;
    }

    public function remove(Author $author)
    {
        $author->delete();
    }

    public function activate(Author $author)
    {
        $author->setActive();
    }

    public function deactivate(Author $author)
    {
        $author->setInactive();
    }

}