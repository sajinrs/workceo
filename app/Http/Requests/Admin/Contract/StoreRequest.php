<?php

namespace App\Http\Requests\Admin\Contract;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'client' => 'required',
            'subject' => 'required',
            'amount' => 'required',
            'contract_type' => 'required|exists:contract_types,id',
            'start_date' => 'required|before:end_date',
            'end_date' => 'required|after:start_date',
        ];

        if($this->start_date >= $this->end_date)
        {
            $rules['start_date'] = 'required|before:end_date';
            $rules['end_date'] = 'required|after:start_date';
          
        } 

        return $rules;
    }
}
