<?php

namespace App\Http\Requests;

use App\Models\Budgeting\Purchase;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'PO' => 'required|numeric',
            'category_id' => 'required|exists:category_masters,id',
            'actual_amount' => 'required|numeric|min:0',
            'department_id' => 'exists:departments,id',
            'fromDept' => 'required',
            'grand_total'=> 'required'
        ];
    }
    
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $actualAmount = $this->input('actual_amount');
        $purchaseId = $this->route('purchase_request');
        $purchase = Purchase::find($purchaseId);

        if (!$purchase) {
            $validator->errors()->add('purchase_request', 'Purchase not found.');
            return;
        }
        // if ($actualAmount && !$this->filled('department_id')) {
        //     $validator->errors()->add('department_id', 'Department is required when actual amount exceeds grand total.');
        //     return;
        // }
        // if ($validator->errors()->any()) {
        //     dd($validator->errors()->all());}
    });
}
}
