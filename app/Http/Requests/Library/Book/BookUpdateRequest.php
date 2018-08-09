<?php

namespace App\Http\Requests\Library\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:books,id,' . $this->book->id,
            'description' => 'required|string',
            'genre' => 'required|integer',
            'author' => 'required|integer',
            'file' => 'required|mimes:txt,doc,docx,fb2,pdf',
        ];
    }
}
