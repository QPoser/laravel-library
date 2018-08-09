<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserEditRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if ($this->has('is_writer')) {
            $this['is_writer'] = true;
        } else {
            $this['is_writer'] = false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:32|unique:users,id,' . $this->user->id,
            'email' => 'required|string|email|max:255|unique:users,id,' . $this->user->id,
            'role' => ['required', 'string', Rule::in(User::rolesList())],
            'is_writer' => 'boolean',
        ];
    }
}
