<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkoutSession extends FormRequest
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
            // 'workout_session_id'     => 'required|exists:workout_sessions,id',
            'start_at'       => 'required|date|before:end_at',
            'end_at'         => 'required|date|after:start_at',
            'sets'           => 'required|array',
            'sets.*.lift_id' => 'required|exists:lifts,id',
            'sets.*.reps'    => 'required|numeric|min:0.5',
            'sets.*.weight'  => 'required|numeric|min:0',
            'sets.*.order'   => 'required|integer|min:1',
            // TODO add a rule to ensure that the order of the sets is unique
        ];
    }
}
