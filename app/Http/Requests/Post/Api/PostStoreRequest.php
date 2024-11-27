<?php

declare(strict_types=1);

namespace App\Http\Requests\Post\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest {
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
            'title'       => 'required|string',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|file|mimes:jpeg,jpg,bmp,png',
            // 'image_url'   => 'nullable|string',
        ];
    }
}
