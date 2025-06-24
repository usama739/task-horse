<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Unauthorized access.');
        // }
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'data' => $user
        ], 201);
    }

    
    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['success' => 'User updated successfully.'], 200);
    }

    
    public function destroy(User $user){
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.'], 200);
    }
}

