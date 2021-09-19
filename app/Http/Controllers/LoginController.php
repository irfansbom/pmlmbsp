<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    //
    public function index()
    {
        // echo csrf_token();
        return view('login');
    }

    public function get_token()
    {
        return csrf_token();
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // dump($request->all());
        // dump($user->password);
        $pw = $user->password;
        if ($user == null) {
            return  response()->json([
                'success' => false,
                'message' => 'email salah',
            ], 200);
        } elseif ($request->password != $pw) {
            return response()->json([
                'success' => false,
                'message' => 'password salah',
            ], 200);
        } else {
            return  response()->json([
                'success' => true,
                'message' => 'Detail Data Post',
                'data'    => $user
            ], 200);
        }
    }
}
