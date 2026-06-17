<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Export;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $users = User::with('profile')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('users.index', compact('users', 'search'));
    }
    
    public function show($id)
    {
        $user = User::with('profile')->findOrFail($id);
        
        $exports = Export::where('user_id', $id)
            ->latest()
            ->paginate(10);
            
        return view('users.show', compact('user', 'exports'));
    }
}