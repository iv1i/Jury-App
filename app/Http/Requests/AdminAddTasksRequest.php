<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAddTasksRequest extends FormRequest
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
            'name' => ['required', 'string','max:255'],
            'category' => ['required', 'string','max:255'],
            'complexity' => ['required','string','max:255'],
            'points' => ['required','int','min:200'],
            'description' => ['required','string'],
            'flag' => ['required','string'],
            'web_port' => 'nullable|integer|between:1024,65535',
            'db_port' => 'nullable|integer|between:1024,65535',
            'sourcecode' => [
                'nullable',
                'file',
                'mimetypes:application/zip,application/x-zip-compressed',
                'mimes:zip',
                'max:10240' // 10MB максимум
            ]
        ];
    }
}
