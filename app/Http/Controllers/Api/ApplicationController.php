<?php

namespace App\Http\Controllers\Api;

use App\Models\Application;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(): JsonResponse
    {
        $applications = Application::all();

        return response()->json($applications, 200);
    }
}
