<?php

namespace App\Http\Requests;

use App\Services\ApiResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * 
     * Trim whitespace and normalize case for the title & author
     * Convert the 'available' field to a boolean; default to true if not provided or invalid
     * Format the date to a standard Y-m-d format
     * convert price to a float 
     * This method normalizes and transforms the incoming data before validation is applied.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'title' => $this->title ? ucwords(trim($this->title)) : null,
            'author' => $this->author ? ucwords(trim($this->author)) : null,
            'available' => filter_var($this->available, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true,
            'published_at' => $this->published_at ? date('Y-m-d', strtotime($this->published_at)) : null,
            'price' => $this->price ? floatval($this->price) : null,
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
            'title' => 'required|string|max:200|unique:books,title',
            'author' => 'required|string|max:100|min:3',
            'description' => 'required|string|min:3|max:500',
            'category_id' => 'required|integer|exists:categories,id',
            'available' => 'boolean',
            'published_at' => 'required|date_format:Y-m-d',
            'price' => 'required|numeric|min:0|max:999.99',
        ];
    }

    /**
     * Custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'book title',
            'author' => 'author name',
            'description' => 'book description',
            'category_id' => 'category',
            'published_at' => 'published date',
            'available' => 'book availability',
            'price' => 'book price',
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute is required.',
            'unique' => 'This :attribute has already been taken.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute cannot exceed :max characters.',
            'exists' => 'The selected :attribute does not exist.',
            'date_format' => 'The :attribute must be in the format YYYY-MM-DD.',
            'numeric' => 'The :attribute must be a valid number.',
            'boolean' => 'The :attribute field must be true(1) or false(0).',
        ];
    }

    /**
     * Handle validation errors and throw an exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
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
