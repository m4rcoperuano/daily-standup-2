<?php

namespace App\Actions\Socialite;

use App\Models\User;
use App\Services\AtlassianIntegration;
use Laravel\Socialite;

class SaveAtlassianIntegration
{
    public function execute(
        User $user,
        Socialite\Contracts\User $socialiteUser
    ) {
        $socialiteIntegration = $user->socialiteIntegrations()->updateOrCreate(
            [
                'provider' => 'atlassian'
            ],
            [
                'access_token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
                'provider_user_name' => $socialiteUser->getName(),
                'provider_user_avatar' => $socialiteUser->getAvatar(),
                'provider_user_email' => $socialiteUser->getEmail(),
                'provider_user_nick_name' => $socialiteUser->getNickname(),
                'provider_user_id' => $socialiteUser->getId(),
            ]
        );

        $atlassianIntegration = new AtlassianIntegration($socialiteIntegration);
        $resources = $atlassianIntegration->getAccessibleResources();

        $socialiteIntegration->update([
            'meta' => [
                'resources' => $resources
            ]
        ]);
    }
}
