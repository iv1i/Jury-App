<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class AdminChangeTeamsRequest extends FormRequest
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
            'id' => ['required', 'numeric', 'integer'],
            'name' => ['required', 'string','max:255'],
            'players' => ['required', 'integer','min:1'],
            'WhereFrom' => ['required','string'],
            'file' => [
                File::image()
                    ->min('1kb')
                    ->max('1mb')
            ]
        ];
    }
}
