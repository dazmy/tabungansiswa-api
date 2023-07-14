<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'grade_id' => 'required|integer|exists:grades,id|unique:users,grade_id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'grade_id' => $request->grade_id,
            'email' => $request->email, 
            'password' => Hash::make($request->password),
        ]);

        $user->assignrole('teacher');

        return new ApiResource(true, 'Registration successful', $user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return new ApiResource(false, 'Invalid credentials', null);
        }

        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password, [])) {
            return new \Exception('Error in Login');
        }

        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return new ApiResource(true, 'Login successful', ['token' => $tokenResult, 'type' => 'Bearer', 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return new ApiResource(true, 'Logout successful', null);
    }
}
