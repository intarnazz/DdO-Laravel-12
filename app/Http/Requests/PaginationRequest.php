<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'limit' => 1,
            'offset' => 0,
            'search' => '',
            'order' => 'updated_at',
            'desc' => true,
            'names' => '',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => 'integer|min:1|max:200',
            'offset' => 'integer|min:0',
            'search' => 'string|min:0|max:255',
            'order' => 'string|min:0|max:255',
            'desc' => 'boolean',
            'names' => 'string|min:0|max:255',
        ];
    }
}
