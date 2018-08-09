<?php

namespace App\Http\Requests\Library\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookCreateRequest extends FormRequest
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
        $authorValidate = [
            'author' => is_string($this->author) ? 'required|string' : 'required|integer',
        ];

        $genreValidate = [
            'genre' => is_string($this->genre) ? 'required|string' : 'required|integer',
        ];

        return array_merge([
            'title' => 'required|string|max:255|unique:books',
            'description' => 'required|string',
            'file' => 'required|mimes:txt,doc,docx,fb2,pdf',
        ], $authorValidate, $genreValidate);
    }
}
