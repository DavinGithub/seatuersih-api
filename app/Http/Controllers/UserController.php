<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        if ($request->email == User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
            ], 409);
        } elseif ($request->phone == User::where('phone', $request->phone)->first()) {
            return response([
                'message' => 'Phone already exists',
            ], 409);
        }

        $userdata = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ];
        $user = User::create($userdata);    
        $token = $user->createToken('seatuersih')->plainTextToken;

        return response([
            'user' => $user,    
            'token' => $token,
        ], 201);
    }

    public function login (LoginRequests $request) {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('seatuersih')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ], 200);
    }
        
    public function details() {
        return response([
            'user' => auth()->user(),
        ], 200);
    }

    public function logout() {
        $user = User::where('email', auth()->user()->email)->first();
        $user->tokens()->delete();
        return response([
            'message' => 'Logged out',
        ], 200);
    }

    // Update Profile

    public function updateUsername(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $user = User::where('email', auth()->user()->email)->first();
        if ($request->username == $user->username) {
            return response([
                'message' => 'Username Cannot be the same as the previous one',
            ], 409);
        }
        $user->username = $request->username;
        $user->save();

        return response([
            'message' => 'Username updated',
            'user' => $user,
        ], 200);
    }
   
    public function updateEmail(Request $request) {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::where('email', auth()->user()->email)->first();
        if ($request->email == $user->email) {
            return response([
                'message' => 'Email Cannot be the same as the previous one',
            ], 409);
        } elseif ($request->email == User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exists',
            ], 409);    
        }
        $user->email = $request->email;
        $user->save();

        return response([
            'message' => 'Email updated',
            'user' => $user,
        ], 200);
    }

    public function updatePhone(Request $request) {
        $request->validate([
            'phone' => 'required|string|max:255|unique:users',
        ]);

        $user = User::where('email', auth()->user()->email)->first();
        if ($request->phone == $user->phone) {
            return response([
                'message' => 'Phone Cannot be the same as the previous one',
            ], 409);
        } elseif ($request->phone == User::where('phone', $request->phone)->first()) {
            return response([
                'message' => 'Phone already exists',
            ], 409);
        }
        $user->phone = $request->phone;
        $user->save();

        return response([
            'message' => 'Phone updated',
            'user' => $user,
        ], 200);
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', auth()->user()->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return response([
            'message' => 'Password updated',
            'user' => $user,
        ], 200);
    }

}   