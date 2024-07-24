<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $userdata = [
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ];

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures');
            $userdata['profile_picture'] = $path;
        }

        $user = User::create($userdata);
        $token = $user->createToken('seatuersih')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequests $request)
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Email atau kata sandi salah',
            ], 401);
        }

        $token = $user->createToken('seatuersih')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function details()
    {
        return response([
            'user' => auth()->user(),
        ], 200);
    }

    public function logout()
    {
        $user = User::where('email', auth()->user()->email)->first();
        $user->tokens()->delete();
        return response([
            'message' => 'Logged out',
        ], 200);
    }

    public function updateUsername(Request $request)
    {
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

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user = User::where('email', auth()->user()->email)->first();
        if ($request->email == $user->email) {
            return response([
                'message' => 'Email Cannot be the same as the previous one',
            ], 409);
        } elseif (User::where('email', $request->email)->exists()) {
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

    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:255|unique:users',
        ]);

        $user = User::where('email', auth()->user()->email)->first();
        if ($request->phone == $user->phone) {
            return response([
                'message' => 'Phone Cannot be the same as the previous one',
            ], 409);
        } elseif (User::where('phone', $request->phone)->exists()) {
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

    public function updatePassword(Request $request)
    {
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

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::where('id', auth()->user()->id)->first(); 

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->extension();
            $image->move(storage_path('/app/profile_pictures/'), $imageName);
            $user->profile_picture = $imageName;
            $user->save();

            return response([
                'message' => 'Profile picture updated',
                'user' => $user,
            ], 200);
        } else {
            return response([
                'message' => 'No profile picture uploaded',
            ], 400);
        }
    }

    public function updateUser(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
        ]);

        $user = User::where('id', auth()->user()->id)->first();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        return response([
            'status' => 'success',
            'message' => 'Change user detail successfully',
            'user' => $user,
        ], 200);
    }
}
