<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * Controller responsible for handling application approvals.
 * 
 * Converts a registration application into a user account and removes the application.
 */
class AcceptanceOfApplicationController extends Controller
{
     /**
     * Stores a new user based on the application data and deletes the original application.
     *
     * @param Application $application The registration application containing user data.
     * 
     * @return \Illuminate\Http\Response HTTP 204 No Content response on success.
     */
    public function store(Application $application): Response
    {

        User::create([
            'name' => $application->name,
            'email' => $application->email,
            'role' => $application->role,
            'password' => $application->password
        ]);

        $application->delete();

        return response()->noContent();
    }

    public function destroy(Application $application): Response
    {
        $application->delete();

        return response()->noContent();
    }
}
