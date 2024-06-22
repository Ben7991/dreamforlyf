<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProductRequest extends FormRequest
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
            "name" => "bail|required|regex:/^[a-zA-Z ]+$/",
            "quantity" => "bail|required|regex:/^[0-9]+$/",
            "image" => "nullable|image",
            "price" => "bail|required|regex:/^[0-9]+(\.[0-9]{2})*$/",
            "status" => "bail|required|in:in-stock,out-of-stock",
        ];
    }
}
