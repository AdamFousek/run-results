<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'raceId' => 'required|integer|exists:App\Models\Race,id',
            'runnerId' => 'required|integer|exists:App\Models\Runner,id',
            'time' => 'nullable|string',
            'startingNumber' => 'nullable|integer',
            'position' => 'nullable|integer',
            'categoryPosition' => 'nullable|integer',
            'category' => 'nullable|string',
            'DNF' => 'nullable|boolean',
        ];
    }
}
