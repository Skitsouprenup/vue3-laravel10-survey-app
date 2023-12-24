<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserCredentialsController extends Controller
{

  public function register(Request $request) {
    $data = $request->validate([
      'name' => 'required|string',
      /*
        unique:<table-name>,<table-column-name>
      */
      'email' => 'required|email|string|unique:users,email',
      'password' => [
        'required',
        /*
          There must be another field for password confimation.
          In the documentation the field name must be: <field>_confirmation
          in this project the field name must be password_confirmation
        */
        'confirmed',
        Password::min(6)->mixedCase()->numbers()->symbols()
      ]
    ]);

    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),

    ]);


    /*
      Login user once registration is complete
    */
    $token = $user->createToken('main')->plainTextToken;

    return response([
      'user' => $user,
      'token' => $token
    ], 201);
  }

  public function login(Request $request) {
    $credentials = $request->validate([
      'email' => 'required|email|string|exists:users,email',
      'password' => 'required',
      'remember' => 'boolean'
    ]);

    $remember = $credentials['remember'];
    unset($credentials['remember']);

    /*
      If authentication is complete. $request->user()
      will have value of the authenticated user
    */
    if(!Auth::attempt($credentials, $remember)) {
      return response([
        'error' => 'Invalid credentials'
      ], 401);
    }

    $user = $request->user();
    $token = $user->createToken('main')->plainTextToken;

    return response([
      'user' => $user,
      'token' => $token
    ], 201);
  }

  public function logout(Request $request) {
    $user = $request->user();
    $user->currentAccessToken()->delete();

    return response([
      'success' => true
    ]);
  }
}
