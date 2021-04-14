<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
        'first_name' => ['nullable'],
        'last_name' => ['nullable'],
        'photo' => ['nullable'],
        'description' => ['nullable'],
        'email' => ['required_without:id'],
        // 'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore(request()->field)],
        'password' => ['required_without:id'],
        'course' => ['nullable'],
        'admin' => ['nullable']
      ];
    }
}
