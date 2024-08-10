<?php

namespace App\Http\Controllers;

use App\Http\Resources\SocialiteIntegrationResource;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteIntegrationController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $integrations = $user->socialiteIntegrations;
        return response()->json(
            SocialiteIntegrationResource::collection($integrations)
        );
    }

    public function redirect(string $provider) {
        return match($provider) {
            'github' => Socialite::driver('github')
                ->scopes(['read:user', 'repo', 'offline_access'])
                ->redirect(),
            'atlassian' => Socialite::driver('atlassian')
                ->scopes(['read:jira-work', 'read:page:confluence', 'read:confluence-props', 'read:confluence-user', 'read:confluence-content.summary', 'read:confluence-content.all', 'offline_access'])
                ->redirect(),
            default => abort(404),
        };
    }

    public function callback(Request $request, string $provider) {
        $user = $request->user();
        $socialiteUser = Socialite::driver($provider)->user();

        $user->socialiteIntegrations()->updateOrCreate(
            [
                'provider' => $provider
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

        return response()->redirectTo(route('profile.show'));
    }
}
