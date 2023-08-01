<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('admin.login.login', [
            'title' => 'Login',
            'dropdown' => '0',
        ]);
    }
}
