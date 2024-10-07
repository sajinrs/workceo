<?php

namespace App\Http\Requests\Admin\Vehicle;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends CoreRequest
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

            'vehicle_name'  => 'required',
            "license_plate" => "required|unique:vehicles,license_plate",
            //"operator_id" => "required",
            "year" => "required",
            'make' => 'required',
            'model' => 'required',
        ];
    }
}
