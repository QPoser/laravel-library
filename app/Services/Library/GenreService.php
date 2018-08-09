<?php
/**
 * Created by PhpStorm.
 * User: qposer
 * Date: 08.08.18
 * Time: 21:19
 */

namespace App\Services\Library;

use App\Entities\Library\Book\Genre;
use DB;

class GenreService
{

    public function create(string $name, $active = false): Genre
    {
        return DB::transaction(function () use ($name, $active) {

            /** @var Genre $genre */
            $genre = Genre::make([
                'name' => $name,
                'status' => $active ? Genre::STATUS_ACTIVE : Genre::STATUS_WAIT,
            ]);

            $genre->saveOrFail();

            return $genre;

        });
    }

    public function update(Genre $genre, array $params): Genre
    {
        $genre->update($params);

        return $genre;
    }

    public function remove(Genre $genre)
    {
        $genre->delete();
    }

    public function activate(Genre $genre)
    {
        $genre->setActive();
    }

    public function deactivate(Genre $genre)
    {
        $genre->setInactive();
    }

}