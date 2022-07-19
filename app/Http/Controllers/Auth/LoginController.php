<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['Invalid credentials', '404']);
        }
        $token = $user->createToken('my-app-token')->plainTextToken;

        return response()->json([$token, $user]);
    }
}
