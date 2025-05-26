<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with('tests')
            ->where('role', '!=', 'admin');

        // Получаем текущего пользователя
        $current_user = $request->user();

        // Если пользователь учитель — исключаем и других учителей
        if ($current_user->role === 'teacher') {
            $query->where('role', '!=', 'teacher');
        }

        $users_and_tests = $query->get();

        return response()->json($users_and_tests, 200);
    }

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $tests = $user->tests;
        return response()->json($tests, 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if ($user->role === 'admin') {
            return response()->json(['message' => 'Вы не можете удалить админа'], 403);
        }

        $user->tests()->detach();

        $user->delete();

        return response()->json(['message' => 'Пользователь удалён'], 200);
    }
}
