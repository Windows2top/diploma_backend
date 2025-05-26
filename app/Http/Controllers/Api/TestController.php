<?php

namespace App\Http\Controllers\Api;

use App\Models\Answer;
use App\Models\TestUser;
use App\Models\Question;
use App\Models\Test;
use App\Http\Requests\Api\AnswerRequest;
use App\Http\Requests\Api\QuestionRequest;
use App\Http\Requests\Api\TestRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller responsible for handling CRUD operations related to tests.
 */
class TestController extends Controller
{
    /**
     * Returns a list of all tests.
     *
     * @return JsonResponse JSON response containing all tests.
     */
    public function index(): JsonResponse
    {
        $tests = Test::all();

        return response()->json($tests, 200);
    }

    /**
     * Stores a new test along with its questions and answers.
     *
     * @param TestRequest $request The incoming request containing test, questions, and answers data.
     *
     * @return JsonResponse JSON response indicating successful creation.
     */
    public function store(TestRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $created_test = Test::create([
                'title' => $request->title,
                'description' => $request->description,
                'lection' => $request->lection
            ]);

            $questions = $request->questions;

            foreach ($questions as $question) {
                $created_question = Question::create([
                    'test_id' => $created_test->id,
                    'title' => $question['title'],
                    'text' => $question['text'],
                    'type' => $question['type']
                ]);

                $answers = $question['answers'];

                foreach ($answers as $answer) {
                    Answer::create([
                        'question_id' => $created_question->id,
                        'title' => $answer['title'],
                        'is_correct' => $answer['is_correct']
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Тест создан'], 201);
    }

     /**
     * Displays the specified test.
     *
     * @param Test $test The test model instance.
     *
     * @return JsonResponse JSON response with test data.
     */
    public function show(Test $test): JsonResponse
    {
        return response()->json($test, 200);
    }

    /**
     * Displays the specified test with questions and answers.
     *
     * @param Test $test The test model instance.
     *
     * @return JsonResponse JSON response with full test data including questions and answers.
     */
    public function edit(Test $test): JsonResponse
    {
        $test = $test->load('questions.answers');
        return response()->json($test, 200);
    }    

    /**
     * Updates the specified test along with its related questions and answers.
     *
     * @param Test $test The test model instance to update.
     * @param TestRequest $request The request containing updated data.
     *
     * @return JsonResponse JSON response indicating success.
     */
    public function update(Test $test, TestRequest $request): JsonResponse
    {
        DB::transaction(function () use ($test, $request) {

            $test->update([
                'title' => $request->title,
                'description' => $request->description,
                'lection' => $request->lection
            ]);

            $existing_questions = $test->questions()->with('answers')->get();
            $updated_question_ids = [];

            foreach ($request->questions as $questionData) {
                if (isset($questionData['id'])) {
                    $question = Question::find($questionData['id']);
                    if ($question) {
                        $question->update([
                            'title' => $questionData['title'],
                            'text' => $questionData['text'],
                            'type' => $questionData['type']
                        ]);
                    }
                    $updated_question_ids[] = $questionData['id'];
                } else {
                    $question = Question::create([
                        'test_id' => $test->id,
                        'title' => $questionData['title'],
                        'text' => $questionData['text'],
                        'type' => $questionData['type']
                    ]);
                    $updated_question_ids[] = $question->id;
                }

                // Тут обрабатываем ответы
                $existing_answers = $question->answers()->get();
                $updated_answer_ids = [];

                foreach ($questionData['answers'] as $answerData) {
                    if (isset($answerData['id'])) {
                        $answer = Answer::find($answerData['id']);
                        if ($answer) {
                            $answer->update([
                                'title' => $answerData['title'],
                                'is_correct' => $answerData['is_correct']
                            ]);
                        }
                        $updated_answer_ids[] = $answerData['id'];
                    } else {
                        $newAnswer = Answer::create([
                            'question_id' => $question->id,
                            'title' => $answerData['title'],
                            'is_correct' => $answerData['is_correct']
                        ]);
                        $updated_answer_ids[] = $newAnswer->id;
                    }
                }

                // Удаляем старые ответы, которые не переданы в запросе
                foreach ($existing_answers as $existingAnswer) {
                    if (!in_array($existingAnswer->id, $updated_answer_ids)) {
                        $existingAnswer->delete();
                    }
                }
            }

            // Удаляем старые вопросы, которых нет в обновленных
            foreach ($existing_questions as $question) {
                if (!in_array($question->id, $updated_question_ids)) {
                    $question->delete();
                }
            }
        });

        return response()->json(['message' => 'Данные обновлены']);
    }


    public function destroy(Test $test): JsonResponse
    {
        if (TestUser::where('test_id', $test->id)->exists()) {
            TestUser::where('test_id', $test->id)->delete();
        }

        $test->delete();

        return response()->json(['message' => 'Лекция удалена'], 200);
    }
}