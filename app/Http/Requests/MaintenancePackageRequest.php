<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MaintenancePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === "ADMIN";
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "duration_in_months" => "bail|required|integer",
            "total_products" => "bail|required|integer",
            "total_price" => "bail|required|regex:/^[0-9]+(\.[0-9]{2})?$/",
            "bv_point" => "bail|required|integer",
        ];
    }
}
