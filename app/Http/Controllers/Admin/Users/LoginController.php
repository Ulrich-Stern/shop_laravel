<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\c;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin/users/login', [
            'title' => "Đăng nhập"
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email:filter',
            'password' => 'required' 
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if(Auth::attempt(['email' => $email, 'password' => $password], $request->input('remember'))) {
            return redirect()->route('admin');
        }
        Session::flash('error', 'Email hoặc mật khẩu không đúng');
        
        return redirect()->back();
    }

}
