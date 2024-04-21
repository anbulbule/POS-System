<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($user->email)->plainTextToken;
            return response([
                'status' => true,
                'message' => 'User Logged in successfully',
                'data' => $user,
                'token' => $token
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Invalid Credentials',
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|unique:users',
            'city' => 'required',
            'mobile' => 'required|unique:users',
            'password' => 'required|confirmed',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ];

        $user = User::create($data);
        $token = $user->createToken($user->email)->plainTextToken;
        if ($user) {
            return response([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => $user,
                'token' => $token
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function profile()
    {
        $user = Auth::user();
        if ($user) {
            return response([
                'status' => true,
                'message' => 'User profile fetched sucessfully',
                'data' => $user
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Failed to fetch User profile',
                'data' => []
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'username'=>'required',
            'city'=>'required',
            'address'=>'required',
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
