<?php

namespace App\Http\Requests;

use App\Models\Admission;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AdmissionUpdateRequest extends FormRequest
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
        $setting = Admission::findOrFail(request('id'));
        return [
            'name' => ['required','string'],
            'email' => ['required','email','unique:users,email,'.$setting->user->id],
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
            'email.required' => 'Email address must be unique',
            'batch_id.required' => 'Batch is required',
            'amount.required' => 'Amount is required',
            'name.required' => 'Name is required',
            'discount.required' => 'Discount is required',
            'payment_status.required' => 'Payment Status is required',
            'bank_status.required' => 'Bank Status  is required',
        ];
    }
}
