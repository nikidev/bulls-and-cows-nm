<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuessNumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'guessNumber' => 'required|regex:/^(?!.*(.).*\1)[0-9]{4}$/'
        ];
    }
}
