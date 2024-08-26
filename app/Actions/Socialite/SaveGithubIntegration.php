<?php

namespace App\Actions\Socialite;

use App\Models\User;
use Laravel\Socialite;

class SaveGithubIntegration
{
    public function execute(
        User $user,
        Socialite\Contracts\User $socialiteUser
    ) {
        $user->socialiteIntegrations()->updateOrCreate(
            [
                'provider' => 'github'
            ],
            [
                'access_token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
                'provider_user_name' => $socialiteUser->getName(),
                'provider_user_avatar' => $socialiteUser->getAvatar(),
                'provider_user_email' => $socialiteUser->getEmail(),
                'provider_user_nick_name' => $socialiteUser->getNickname(),
                'provider_user_id' => $socialiteUser->getId(),
                'version' => '1.0.0'
            ]
        );
    }
}
