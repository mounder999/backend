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
       
        $request->validate([
            'Adresse_Email' => 'required|email',
            'Mot_de_passe' => 'required',
        ]);

       
        if (Auth::attempt(['Adresse_Email' => $request->json('Adresse_Email'), 'password' => $request->json('Mot_de_passe')])) {
            
            $user = Auth::user();

            
            return response()->json(['user' => $user, 'message' => 'Login successful'], 200);
        }

        
        return response()->json(['message' => 'Invalid email or password'], 401);
    }
    // public function logout()
    // {
    //     Auth::logout();

    //     return response()->json(['message' => 'Logout successful'], 200);
    // }
}
