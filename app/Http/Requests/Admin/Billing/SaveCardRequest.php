<?php

namespace App\Http\Requests\Admin\Billing;

use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;

class SaveCardRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'card_number' => 'required',
            'year' => ['required', new CardExpirationYear($this->get('month'))],
            'month' => ['required', new CardExpirationMonth($this->get('year'))],
            //'month' => 'required',
            //'year' => 'required',
            'cvv' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'card_number.required' => 'The card number is compulsory',
            'year.validation' => 'Invalid Year'
        ];
    }
}
