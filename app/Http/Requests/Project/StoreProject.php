<?php

namespace App\Http\Requests\Project;

use App\Http\Requests\CoreRequest;

class StoreProject extends CoreRequest
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
        $rules = [
            'project_name' => 'required',
            'start_date' => 'required',

            'client_id' => 'required',
            'category_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'project_budget' => 'required',
            'currency_id' => 'required',
        ];

        if($this->start_date == $this->deadline)
        {
            $rules['end_time'] = 'required|after:start_time';
        }       

        if(!$this->has('without_deadline')){
            $rules['deadline'] = 'required|after_or_equal:start_date';
        }

        return $rules;
    }
}
