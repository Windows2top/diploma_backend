<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
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
            'description' => ['required'],
            'title' => ['required', 'max:60'],
            'lection' => 'required',
            'questions' => ['required', 'array'],
            'questions.*.title' => ['required', 'max:60'],
            'questions.*.text' => 'required',
            'questions.*.answers' => ['required', 'array'],
            'questions.*.answers.*.title' => ['required', 'max:60'],
            'questions.*.answers.*.is_correct' => 'required|boolean'
        ];
    }

    public function messages(): array {
        return [
            'description.required' => 'Описание должно быть обязательн',
            'title.required' => 'Заголовок теста обязателен.',
            'title.max' => 'Заголовок теста не должен превышать 60 символов.',
            'lection.required' => 'Лекции обязаны быть указаны.',
            'questions.required' => 'Вопросы обязательны.',
            'questions.array' => 'Поле вопросов должно быть массивом.',
            'questions.*.title.required' => 'Каждый вопрос должен иметь заголовок.',
            'questions.*.title.max' => 'Заголовок вопроса не должен превышать 60 символов.',
            'questions.*.text.required' => 'Текст вопроса обязателен.',
            'questions.*.answers.required' => 'Ответы на вопрос обязательны.',
            'questions.*.answers.array' => 'Поле ответов должно быть массивом.',
            'questions.*.answers.*.title.required' => 'Каждый ответ должен иметь заголовок.',
            'questions.*.answers.*.title.max' => 'Заголовок ответа не должен превышать 60 символов.',
            'questions.*.answers.*.is_correct.required' => 'Каждый ответ должен содержать информацию о правильности.',
            'questions.*.answers.*.is_correct.boolean' => 'Поле is_correct должно быть булевым значением (true/false).',
        ];
    }
}
