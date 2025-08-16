<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyClerkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['error' => 'Missing token'], 401);
        }

        try {
            $rawKeyBase64 = config('services.clerk.pem_public_key_base64');
            // Decode from base64 to get the original PEM content
            $publicKey = base64_decode($rawKeyBase64);
            $publicKey = str_replace("\r\n", "\n", $publicKey);
            $payload = (array) JWT::decode($token, new Key($publicKey, 'RS256'));
            $clerkId = $payload['sub'];
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token: '.$e->getMessage()], 401);
        }

        $user = User::where('clerk_id', $clerkId)->first();
        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        auth()->login($user);       // sets the Laravel auth user

        return $next($request);
    }
}
