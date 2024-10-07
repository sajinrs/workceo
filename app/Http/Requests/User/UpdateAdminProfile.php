<?php

namespace App\Http\Requests\User;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminProfile extends CoreRequest
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
        //print_r($this->route()->parameters());
        return [
            'email' => 'required|unique:users,email,'.$this->route('profile_setting'),
            //'name'  => 'required',
            'first_name'  => 'required',
            'last_name'  => 'required',
           // 'image' => 'image|max:2048'
        ];
    }

    /* public function messages() {
        return [
          'image.image' => 'Profile picture should be an image'
        ];
    } */
}
