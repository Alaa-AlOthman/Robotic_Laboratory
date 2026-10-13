<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
     // User login


public function login(Request $request)
{
    $credentials = $request->only('name', 'password');

    try {
        $user = User::where('name', $credentials['name'])->first();

        if (! $user) {
            return response()->json(['error' => 'المستخدم غير موجود'], 404);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'كلمة المرور غير صحيحة'], 401);
        }

        // Create token
        $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

        return response()->json(compact('token'));
    } catch (JWTException $e) {
        return response()->json(['error' => 'تعذر إنشاء التوكن'], 500);
    }
}


     // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $user->load('team');
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

}
