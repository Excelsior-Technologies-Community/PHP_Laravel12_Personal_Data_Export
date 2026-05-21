<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->paginate(10);
        return view('users.index', compact('users'));
    }
    
    public function show($id)
    {
        $user = User::with('profile')->findOrFail($id);
        $exports = \App\Models\Export::where('user_id', $id)->latest()->take(5)->get();
        return view('users.show', compact('user', 'exports'));
    }
}