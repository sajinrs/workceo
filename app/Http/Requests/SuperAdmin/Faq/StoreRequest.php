<?php

namespace App\Http\Requests\SuperAdmin\Faq;

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
            'type' => 'required',
            'external_url'       => 'required_if:type,external',
            'internal_url'       => 'required_if:type,internal',
            'description'       => 'required_if:type,internal',
            'image'         => 'mimes:jpeg,jpg,png,gif|required|dimensions:width=270,height=150',
        ];

        return $data;
    }


    public function messages() {
        return [
            'external_url.required_if' => __('The external field is required'),
            'internal_url.required_if' => __('The internal field is required'),
            'description.required_if' => __('The description field is required')
        ];
    }
}
