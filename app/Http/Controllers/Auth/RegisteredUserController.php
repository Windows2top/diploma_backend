<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Application::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->symbols()], //валидация
        ], [
            'name.required' => 'Поле имя должно быть заполненным',
            'email.required' => 'Поле почты должно быть заполненным',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Пароль должен иметь минимум 8 символов',
            'password.letters' => 'Пароль должен иметь хотя-бы одно число',
            'password.numbers' => 'Пaроль должен иметь хотя-бы одну букву',
            'password.symbols' => 'Пароль должен иметь спец.символ',
        ]);
        
        $user = Application::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->string('password')),
        ]);

        return response()->json(['application_message' => 'Заявка отправлена']);
    }
}
