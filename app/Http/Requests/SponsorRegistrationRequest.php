<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SponsorRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::check();
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
            "phone_number" => "bail|required|regex:/^[0-9]+$/",
            "wave" => "bail|nullable|regex:/^[0-9]+$/",
            "package_id" => "required",
            "upline_wave" => "nullable",
            "type" => "required",
            "stockist_id" => "required"
        ];
    }
}
