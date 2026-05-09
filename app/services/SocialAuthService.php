<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialAuthService
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        return $this->findOrCreateUser($socialUser, $provider);
    }

    private function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(str()->random(16)),

                // optional fields
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        return $user;
    }
}