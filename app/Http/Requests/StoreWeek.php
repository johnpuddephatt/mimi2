<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class StoreWeek extends FormRequest
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
    public function rules(Request $request)
    {
      $course_id = $this->route('course')->id;
      $week = $this->route('week');
      return [
        'name' => 'required|max:255',
        'description' => 'nullable',
        'number' => Rule::unique('weeks')->ignore($week ? $week->id : null)->where(function ($query) use ($course_id) {
          return $query->where('course_id', $course_id);
        }),
        'is_chatroom' => 'boolean'
      ];
    }
}
