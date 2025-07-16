<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'clerk_id' => 'required|string',
        ]);

        $user = User::where('clerk_id', $request->clerk_id)->first();

        if (!$user) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        $organization = Organization::create([
            'name' => $request->name,
        ]);

        $user->organization_id = $organization->id;
        $user->save();


        return response()->json([
            'message' => 'Organization created',
            'organization' => $organization,
        ], 201);    
    }
}
