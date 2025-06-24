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
        // return view('users.index', compact('users'));

        return response()->json($users);
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

         return response()->json([
            'message' => 'User created successfully.',
            'data' => $user
        ], 201);
        // return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    
    public function edit(User $user){
        return response()->json($user);
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
        // return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

