<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class AdminAddTeamsRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'players' => ['required', 'integer', 'min:1'],
            'WhereFrom' => ['required', 'string'],
            'password' => ['required','string','min:6'],
            'file' => [
                File::image()
                    ->min('1kb')
                    ->max('1mb')
            ]
        ];
    }
}
