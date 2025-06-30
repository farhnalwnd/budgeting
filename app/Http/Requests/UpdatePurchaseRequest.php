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
            'PO' => 'nullable|numeric',
            'category_id' => 'nullable|exists:category_masters,id',
            'actual_amount' => 'nullable|numeric|min:0',
            'department_id' => 'nullable|exists:departments,id',
            'fromDept' => 'required',
            'grand_total'=> 'required'
        ];
    }

    public function withValidator($validator)
    {
        // $validator->after(function ($validator) {
        //     $actualAmount = floatval($this->input('actual_amount', 0));
        //     $purchaseId = $this->route('purchase_request');
        //     $purchase = Purchase::find($purchaseId);

        //     if (!$purchase) {
        //         $validator->errors()->add('purchase_request', 'Purchase not found.');
        //         return;
        //     }

        //     $baseAmount = $purchase->actual_amount > 0 ? $purchase->actual_amount : $purchase->grand_total;

        //     $departmentId = $this->input('department_id');
        //     $department = null;
        //     $deptBalance = 0;

        //     if ($departmentId) {
        //         $department = \App\Models\Department::find($departmentId);
        //         if ($department) {
        //             $deptBalance = floatval($department->balanceForYear(now()->year));
        //         }
        //     }

        //     // Hitung selisih seperti di JavaScript
        //     $difference = $actualAmount - $baseAmount - $deptBalance;

        //     if ($difference > 0 && !$departmentId) {
        //         $validator->errors()->add('department_id', 'Department is required because actual amount exceeds budget and department balance.');
        //     }
        // });
    }
}
