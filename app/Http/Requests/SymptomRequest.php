<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SymptomRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'severity' => 'required|in:mild,moderate,severe',
            'description',
            'date_recorded' => 'date|required',
            'notes' => 'array'
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'the name limit is 100',
            'severity.in' => 'choose from these options mild,moderate,severe',
            'date_recorded.date' => 'should be a date formate',
        ];
    }
}
