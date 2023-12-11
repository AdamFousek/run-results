<?php

namespace App\Http\Requests;

use App\Models\PairRunnerLog;
use Illuminate\Foundation\Http\FormRequest;

class StorePairRunnerLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', PairRunnerLog::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'runnerId' => 'required|exists:runners,id',
            'day' => 'required|integer',
            'month' => 'required|integer',
        ];
    }
}
