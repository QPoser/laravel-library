<?php

namespace App\Http\Resources\User;

use App\Entities\Library\Book;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'books' => array_map(function (array $book) {
                return [
                    'id' => $book['id'],
                    'title' => $book['title'],
                ];
            }, $this->books->toArray()),
        ];
    }
}
