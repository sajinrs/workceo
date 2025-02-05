<?php

namespace App\Http\Requests\Admin\Vehicle;

use App\Vehicle;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends CoreRequest
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

        $detailID = Vehicle::where('id', $this->id);
        return [

            'vehicle_name'  => 'required',
            "license_plate" => "required",
            "year" => "required",
            'make' => 'required',
            'model' => 'required',
        ];

        /* $detailID = EmployeeDetails::where('user_id', $this->route('employee'))->first();
        return [
//            'employee_id' => 'required|unique:employee_details,employee_id,'.$detailID->id,
            'employee_id' => [
                'required',
                Rule::unique('employee_details')->where(function($query) {
                    $query->where(['employee_id' => $this->request->get('employee_id'), 'company_id' => company()->id]);
                })->ignore($detailID->id, 'id')
            ],
            'email' => 'required|email|unique:users,email,'.$this->route('employee'),
            'slack_username' => 'nullable|unique:employee_details,slack_username,'.$detailID->id,
            //'name'  => 'required',
            'first_name'  => 'required',
            "last_name" => "required",
            'hourly_rate' => 'nullable|numeric',
            'department' => 'required',
            'designation' => 'required',
        ]; */
    }
}
