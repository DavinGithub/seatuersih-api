<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'notification_token' => 'nullable|string',  
        ]);

        $user = Admin::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Menetapkan dan menyimpan notification_token
        if ($request->has('notification_token')) {
            $user->notification_token = $request->notification_token;
            $user->save();
        }

        $token = $user->createToken('seatuersih')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'notification_token' => 'nullable|string', // Menambahkan validasi untuk notification_token
        ]);

        $user = Admin::where('email', $request->email)->first();
        if ($user != null) {
            return response([
                'message' => 'Email already exists',
            ], 409);
        }

        $userdata = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'notification_token' => $request->notification_token, 
        ];

        $user = Admin::create($userdata);
        $token = $user->createToken('seatuhersih')->plainTextToken;

        return response([
            'admin' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout()
    {
        $user = Admin::where('email', auth()->user()->email)->first();
        $user->notification_token = null;
        $user->save();
        $user->tokens()->delete();

        return response([
            'message' => 'Logged out',
        ], 200);
    }

    public function getUser()
    {
        $user = Auth::guard('admin')->user();
        $user = User::all();

        return response(['status' => 'success',
            'message' => 'User fetched successfully',
            'user' => $user,
        ], 200);
    }

    public function getOrder()
    {
        $admin = Auth::guard('admin')->user();
    }
}
