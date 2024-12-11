<?php

declare(strict_types=1);

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array {
        // dd($this->all());
        return [
            'title'           => 'required|string',
            'description'     => 'nullable|string',
            'days_for_create' => 'nullable|integer',
            'image_url'       => 'nullable|file|mimes:jpeg,jpg,bmp,png',
            // 'image_url'   => 'nullable|string',
        ];
    }
}
