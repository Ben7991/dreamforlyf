<?php

namespace App\Http\Requests;

use App\Models\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRegistrationPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === UserType::ADMIN->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "bail|required|regex:/^[a-zA-Z ]+$/|unique:registration_packages,name",
            "price" => "bail|required|regex:/^[0-9]+(\.[0-9]{2})*$/",
            "bv_point" => "bail|required|regex:/^[0-9]+$/",
            "cutoff" => "bail|required|regex:/^[0-9]+(\.[0-9]{2})*$/",
        ];
    }
}
