<?php

namespace App\Http\Requests\Library\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'search' => 'nullable|string',
            'genre' => 'nullable|int',
            'author' => 'nullable|int',
        ];
    }
}
