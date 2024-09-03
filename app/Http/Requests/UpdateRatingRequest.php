<?php

namespace App\Http\Requests;

use App\Models\Rating;
use App\Models\BorrowRecords;
use App\Services\ApiResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRatingRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userId = $this->user()->id;
        $bookId = $this->route('bookId');

        // Fetch the rating based on the provided bookId and userId
        $rating = Rating::where('book_id', $bookId)
            ->where('user_id', $userId)
            ->first();

        // Return true if the rating exists and belongs to the authenticated user
        return !is_null($rating);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $rating = $this->input('rating');

        $this->merge([
            'user_id' => $this->user()->id,
            'book_id' => $this->route('bookId'),
            'rating' => is_numeric($rating) ? (int) $rating : $rating
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
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ];
    }
    protected function convertToInteger($rating): int
    {
        return is_numeric($rating) ? (int) $rating : $rating;
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
            'rating' => 'rating',
            'review' => 'review',
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
            'integer' => 'The :attribute must be an integer between 1 and 5.',
            'min' => 'The :attribute must be at least 1.',
            'max' => 'The :attribute cannot exceed 500 characters.',
            'required' => 'The :attribute field is required when provided.',
            'exists' => 'The selected :attribute does not exist.',
        ];
    }

    /**
     * Handle failed authorization.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        if (!Auth::check()) {
            throw new HttpResponseException(
                ApiResponseService::error([], 'You are not logged in.', 401)
            );
        }

        throw new HttpResponseException(
            ApiResponseService::error([], 'You do not own this rating.', 403)
        );
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
