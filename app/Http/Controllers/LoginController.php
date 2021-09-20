<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
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
        $user = Petugas::where('uname', $request->username)->first();
        $pw = $user->pwd;
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

    public function loginweb(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $pwdb = Petugas::where('uname', $username)->first();
        if ($pwdb != null && $password == $pwdb->pwd) {
            dump($pwdb->u);
            $request->session()->put('username', $pwdb->uname);
            $request->session()->put('level', $pwdb->level);
            $request->session()->put('kode_wil', $pwdb->kd_kab);
            // dump(session()->has('username'));
            // if($pwdb ){

            // }
            if (session('level') == "ADMINKAB") {
                return redirect()->action([ReportController::class, 'adminkab'], ['kab' => $pwdb->kode_wil]);
            }
        } else {
            // dump('salah');
            return redirect()->action([LoginController::class, 'index'], ['alert' => 'yes']);
        }
    }

    public function logout()
    {
        session()->forget('username');
        return  redirect()->action([LoginController::class, 'index']);
    }
}
