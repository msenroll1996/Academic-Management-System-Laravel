<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'email' => ['required','email'],
            'batch_id' => ['required','numeric'],
            'amount' => ['required'],
            'date' => ['required','date'],
            'discount' => ['required','numeric'],
            'payment_status' => ['required','numeric'],
            'bank_status' => ['required','numeric'],
            'transaction_no' => ['nullable','string'],
            'remark' => ['nullable','string']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email address is required',
            'batch_id.required' => 'Batch is required',
            'amount.required' => 'Amount is required',
            'name.required' => 'Name is required',
            'discount.required' => 'Discount is required',
            'payment_status.required' => 'Payment Status is required',
            'bank_status.required' => 'Bank Status  is required',
        ];
    }
}
