<?php

namespace App\Http\Requests\SuperAdmin\PageTipModule;

use App\Http\Requests\SuperAdmin\SuperAdminBaseRequest;

class ArticleUpdateRequest extends SuperAdminBaseRequest
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
        ];

        return $data;
    }
}
