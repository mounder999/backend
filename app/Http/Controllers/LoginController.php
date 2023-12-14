<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utilisateur;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * Login user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the incoming JSON data
        $request->validate([
            'Adresse_Email' => 'required|email',
            'Mot_de_passe' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt(['Adresse_Email' => $request->json('Adresse_Email'), 'password' => $request->json('Mot_de_passe')])) {
            // Authentication successful
            $user = Auth::user();

            // You can add additional information to the response if needed
            return response()->json(['user' => $user, 'message' => 'Login successful'], 200);
        }

        // Authentication failed
        return response()->json(['message' => 'Invalid email or password'], 401);
    }
}
