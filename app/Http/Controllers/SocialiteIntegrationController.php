<?php

namespace App\Http\Controllers;

use App\Actions\Socialite\SaveAtlassianIntegration;
use App\Actions\Socialite\SaveGithubIntegration;
use App\Http\Resources\SocialiteIntegrationResource;
use App\Models\SocialiteIntegration;
use App\Models\Team;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SocialiteIntegrationController extends Controller
{
    use AuthorizesRequests;

    public function show(Request $request, Team $team) {
        return Inertia::render('SocialiteIntegrations/Show', [
            "team" => $team,
        ]);
    }

    public function index(Request $request, Team $team) {
        $user = $request->user();
        $integrations = $user->socialiteIntegrations()
            ->where('team_id', $team->getKey())
            ->get();

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
                ->scopes([
                    'read:jira-work',
                    'read:page:confluence',
                    'read:confluence-props',
                    'read:confluence-user',
                    'read:confluence-content.summary',
                    'read:confluence-content.all',
                    'offline_access',
                    'read:sprint:jira-software',
                    'read:issue-details:jira',
                    'read:board-scope:jira-software',
                    'read:jql:jira',
                    'read:project:jira'
                ])
                ->redirect(),
            default => abort(404),
        };
    }

    public function callback(Request $request, string $provider) {
        $user = $request->user();
        $socialiteUser = Socialite::driver($provider)->user();

        match($provider) {
            'github' => app(SaveGithubIntegration::class)->execute($user, $socialiteUser),
            'atlassian' => app(SaveAtlassianIntegration::class)->execute($user, $socialiteUser),
        };

        return response()->redirectTo(route('profile.show'));
    }

    public function destroy(Request $request, SocialiteIntegration $socialiteIntegration) {
        $this->authorize('delete', $socialiteIntegration);

        $socialiteIntegration->delete();
        return response()->json(['message' => 'Integration deleted']);
    }
}
