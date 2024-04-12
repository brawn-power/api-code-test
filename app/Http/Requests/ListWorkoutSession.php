<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListWorkoutSession extends FormRequest
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
            'lift_id'   => 'nullable|exists:lifts,id',
            'reps'      => 'nullable|numeric|min:0.5',
            'date_from' => 'nullable|required_with:date_to|date|before:date_to',
            'date_to'   => 'nullable|required_with:date_from|date|after:date_from',
        ];
    }
}
