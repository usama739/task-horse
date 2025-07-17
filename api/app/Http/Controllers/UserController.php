<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\TeamInviteMail;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index(){
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $users = User::where('role', 'member')->where('organization_id', auth()->user()->organization_id)->orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }


    public function store(Request $request){
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);


        // 1: Create Clerk user
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('CLERK_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.clerk.com/v1/users', [
            'email_address' => [$request->email],
            'password' => $request->password,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to create user in Clerk.',
                'clerk_response' => $response->json(),
            ], 500);
        }

        $clerkUser = $response->json();

        if (!isset($clerkUser['id'])) {
            return response()->json([
                'error' => 'Missing Clerk user ID.',
                'clerk_response' => $clerkUser,
            ], 500);
        }

        // 2: Create local user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member',
            'clerk_id' => $clerkUser['id'],
            'organization_id' => auth()->user()->organization_id,
        ]);

        // 3: Send custom email
        Mail::to($user->email)->send(new TeamInviteMail($user));

        return response()->json([
            'message' => 'Team member created successfully.',
            'data'    => $user,
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


    public function findByClerkId($clerk_id){
        $user = User::where('clerk_id', $clerk_id)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }
}

