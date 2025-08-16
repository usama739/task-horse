<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClerkWebhookController extends Controller
{
    public function handle(Request $request)
    {

        try {
            $event = $request->get('type');

            if ($event === 'user.created') {

                $data = $request->get('data');

                // Prevent duplicate: only process if this Clerk ID doesn't already exist
                // $existing = User::where('clerk_id', $data['id'])->first();
                $existing = User::where('email', $data['email_addresses'][0]['email_address'])->first();
                if ($existing) {
                    return response()->json(['message' => 'User Already exists'], 200);
                }

                // This should only be triggered when an Admin signs up
                User::create([
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'email' => $data['email_addresses'][0]['email_address'],
                    'clerk_id' => $data['id'],
                    'role' => 'admin',
                ]);
            }

            return response()->json(['message' => 'Webhook handled'], 200);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
