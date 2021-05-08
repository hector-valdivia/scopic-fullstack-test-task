<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'  => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()->id)
            ],
            'max_amount_bid' => 'numeric'
        ];

        if ($this->input('password')){
            $rules['password'] = ['required', 'confirmed'];
        }

        return $rules;
    }
}
