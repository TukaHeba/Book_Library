<?php

namespace App\Http\Requests;

use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBorrowRecordsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => Auth::id(),
            'borrowed_at' => $this->borrowed_at ? date('Y-m-d', strtotime($this->borrowed_at)) : now()->format('Y-m-d'),
            'due_date' => $this->due_date ? date('Y-m-d', strtotime($this->due_date)) : now()->addDays(14)->format('Y-m-d'),
            'returned_at' => null,
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
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'borrowed_at' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d|after_or_equal:borrowed_at',
            'returned_at' => 'nullable|date_format:Y-m-d|nullable|after_or_equal:borrowed_at',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function passedValidation()
    {
        $this->merge([
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14),
            'returned_at' => null,
        ]);
    }

    /**
     * Get the custom attributes for the request's fields.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'book_id' => 'book',
            'user_id' => 'user',
            'borrowed_at' => 'borrowed date',
            'due_date' => 'due date',
            'returned_at' => 'return date',
        ];
    }

    /**
     * Get the custom validation messages for the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required when provided.',
            'exists' => 'The selected :attribute does not exist.',
            'date_format' => 'The :attribute must be in the format YYYY-MM-DD.',
            'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
        ];
    }
    
    /**
     * Handle validation errors and throw an exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator The validation instance.
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(
            ApiResponseService::error($errors, 'A server error has occurred', 422)
        );
    }
}
