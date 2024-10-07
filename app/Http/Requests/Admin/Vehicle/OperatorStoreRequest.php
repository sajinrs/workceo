<?php

namespace App\Http\Requests\Admin\Vehicle;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OperatorStoreRequest extends CoreRequest
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
            'first_name'  => 'required',
            "last_name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
        ];
    }
}
