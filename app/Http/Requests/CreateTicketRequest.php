<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'user_id' => 'exists:users,id|max:255',
            'subject' => 'required|string|max:255|regex:/^[A-Za-z0-9\s\-\.,\'"]*$/',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|string|max:50|regex:/^[A-Za-z0-9\s]*$/',
        ];
    }
}
