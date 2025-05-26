<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'title' => ['required', 'max:60'],
            'text' => 'required',
            'answers' => ['required', 'array'],
            'answers.*.title' => ['required', 'max:60'],
            'answers.*.is_correct' => 'required|boolean'
        ];
    }

    public function messages(): array {
        return [
            'title.required' => 'Заголовок вопроса обязателен.',
            'title.max' => 'Заголовок вопроса не должен превышать 60 символов.',
            'text.required' => 'Текст вопроса обязателен.',
            'answers.required' => 'Ответы на вопрос обязательны.',
            'answers.array' => 'Поле ответов должно быть массивом.',
            'answers.*.title.required' => 'Каждый ответ должен иметь заголовок.',
            'answers.*.title.max' => 'Заголовок ответа не должен превышать 60 символов.',
            'answers.*.is_correct.required' => 'Каждый ответ должен содержать информацию о правильности.',
            'answers.*.is_correct.boolean' => 'Поле is_correct должно быть булевым значением (true/false).',
        ];
    }
}
