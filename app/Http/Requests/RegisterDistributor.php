<?php

namespace App\Http\Requests;

use App\Models\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterDistributor extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === UserType::DISTRIBUTOR->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required",
            "email" => "bail|required|email|unique:users,email",
            "country" => "required",
            "city" => "required",
            "package_id" => "required|numeric",
            "phone_number" => "bail|required|regex:/^[0-9]+$/",
            "wave" => "bail|required|regex:/^[0-9]+$/",
            "leg" => "required",
            "type" => "required",
            "stockist_id" => "required",
            "upline_id_email" => "required"
        ];
    }
}
