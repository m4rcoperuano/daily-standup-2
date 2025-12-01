<?php

namespace App\Providers;

use SocialiteProviders\Atlassian\Provider;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Events\TeamCreated;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Atlassian;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('atlassian', Provider::class);
        });

        Event::listen(function (TeamCreated $event) {
            $event->team->pointingRooms()->create([
                'name' => 'Default Room',
            ]);
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->email === 'marco.j.ledesma@gmail.com' && $user->hasVerifiedEmail();
        });
    }
}
