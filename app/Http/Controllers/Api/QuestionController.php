<?php

namespace App\Http\Controllers\Api;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use App\Http\Requests\Api\QuestionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a list of questions for a given test, including related answers.
     *
     * @param Test $test The test whose questions are to be retrieved.
     *
     * @return JsonResponse JSON response containing all questions with answers.
     */
    public function index(Test $test): JsonResponse
    {
        $questions = Question::where('test_id', $test->id)
        ->with('answers')
        ->get();

        return response()->json($questions, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        $test_id = $request->test_id;
        $request_answers = $request->answers;

        DB::transaction(function () use ($user, $request_answers) {

            $answers = Answer::whereIn('id', $request_answers)->get();
    
            $syncData = [];
            foreach ($answers as $answer) {
                $syncData[$answer->id] = ['is_correct' => $answer->is_correct];
            }
    
            $user->answers()->syncWithoutDetaching($syncData);
        });

        $user_answers = $user->answers()
        ->select('answer_user.answer_id', 'answers.is_correct', 'answers.question_id')
        ->get();

        $grouped_answers = $user_answers->groupBy('question_id');

        $total_questions = $grouped_answers->count();

        // Подсчитываем количество правильно отвеченных вопросов
        $correct_questions = $grouped_answers->filter(function ($current_question_answers, $question_id) {
            // ID всех ответов пользователя на этот вопрос
            $user_answers_ids = $current_question_answers->pluck('answer_id')->sort()->values();

            // ID всех правильных ответов на этот вопрос
            $correct_answer_ids = Answer::where('question_id', $question_id)
                ->where('is_correct', 1)
                ->pluck('id')
                ->sort()
                ->values();

            // Сравниваем: если полностью совпадают — засчитываем
            return $user_answers_ids->toArray() === $correct_answer_ids->toArray();
        })->count();

        if ($correct_questions < $total_questions) {

            $user->answers()->detach();
    
            return response()->json([
                'message' => 'Лекция не усвоена',
                'total_questions' => $total_questions,
                'correct_questions' => $correct_questions
            ], 200);

        } else {

            $user->tests()->syncWithoutDetaching([$test_id]);
    
            $user->answers()->detach();
    
            return response()->json([
                'message' => 'Лекция усвоена',
                'total_questions' => $total_questions,
                'correct_questions' => $correct_questions
            ], 201);
        }
    }

}
