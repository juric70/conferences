<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */

    public function index(){
        $users = User::with('role')->get();
        return response()->json($users, 200);
    }
    public function show_id_by_email(String $email){
        $user = User::with('role')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json($user, 200);
    }
    public function store(LoginRequest $request): Response
    {

        try {
            $request->authenticate();

            $request->session()->regenerate();

            return response()->noContent();

        } catch (ValidationException $e) {
            return response()->json($e->getMessage());
        }

    }
    public function showLoggedInUser(){
        $user = Auth::user();
        if($user == null){
            return \response()->json('No user',404);
        }else{
            return \response()->json($user);
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
