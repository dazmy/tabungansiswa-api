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
            'username' => 'required|string|unique:users,username|min:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'grade_id' => $request->grade_id,
            'username' => $request->username, 
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('teacher');
        
        return new ApiResource(true, 'Registration successful', $user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['username', 'password']);
        if (!Auth::attempt($credentials)) {
            return new ApiResource(false, 'Username atau Password Salah', null);
        }

        $user = User::where('username', $request->username)->first()->load('grade');
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
