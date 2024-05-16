<?php

namespace App\BO\Invoices\v100\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Implement your authorization logic here (e.g., user must be the creator of the invoice)
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
            'first_name' => 'required|string|max:1000',
            'last_name' => 'required|string|max:1000',
            'description' => 'nullable|string|max:3000',
            'payment_term' => 'required|integer|min:1|max:100',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'total_amount' => 'required|numeric|between:0,99999999.99', // This supports up to 8 digits before the decimal and 2 digits after the decimal
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name may not be greater than 1000 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name may not be greater than 1000 characters.',
            'description.max' => 'Description may not be greater than 3000 characters.',
            'payment_term.required' => 'Payment term is required.',
            'payment_term.integer' => 'Payment term must be a whole number.',
            'payment_term.min' => 'Payment term must be at least 1.',
            'payment_term.max' => 'Payment term may not be greater than 100.',
            'invoice_date.required' => 'Invoice date is required.',
            'invoice_date.date' => 'Invoice date must be a valid date.',
            'due_date.required' => 'Due date is required.',
            'due_date.date' => 'Due date must be a valid date.',
            'due_date.after_or_equal' => 'Due date must be on or after the invoice date.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a number.',
            'total_amount.between' => 'Total amount must be a valid monetary amount.',
        ];
    }
}
