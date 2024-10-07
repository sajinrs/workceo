<?php

namespace App\Http\Requests\SuperAdmin\OnBoarding;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class StoreRequest extends SuperAdminBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = [
            'title' => 'required',
            'description' => 'required',
            'icon_code' => 'required',
            'popup_title' => 'required',
            'popup_description' => 'required',
            'popup_link' => 'required',
            'image' => 'required|mimes:jpg,jpeg,gif|dimensions:width=600,height=300',
        ];

        return $data;
    }
}
