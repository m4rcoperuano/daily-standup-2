<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteIntegrationController extends Controller
{
    public function redirect(string $provider) {
        return match($provider) {
            'github' => Socialite::driver('github')
                ->scopes(['read:user', 'repo'])
                ->redirect(),
            'atlassian' => Socialite::driver('atlassian')
                ->scopes(['read:jira-work', 'read:confluence-content.summary', 'offline_access'])
                ->redirect(),
            default => abort(404),
        };
    }

    public function callback(Request $request) {
        dd($request->all());
    }
}
