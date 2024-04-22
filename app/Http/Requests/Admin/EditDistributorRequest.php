<?php

namespace App\Http\Requests\Admin;

use App\Models\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditDistributorRequest extends FormRequest
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
            "name" => "bail|required|regex:/^[a-zA-Z ]+$/",
            "email" => "bail|required|email",
            "country" => "required",
            "city" => "required",
            "phone_number" => "bail|required|numeric",
            "wave_number" => "bail|required|numeric",
            "action" => "required"
        ];
    }
}
