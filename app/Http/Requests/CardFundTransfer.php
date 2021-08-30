<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardFundTransfer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->isJson();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_code'         => ['required', 'digits:3'],
            'account_number'    => ['required', 'digits:10'],
            'account_name'      => ['required'],
            'amount'            => ['required', 'numeric', 'min:50'],
            'payment_method'    => ['required'],
            'reference'         => ['required', 'unique:agent_transactions']
        ];
    }

    public function messages()
    {
        return [
            'reference.unique' => "Invalid transaction reference"
        ];
    }
}
