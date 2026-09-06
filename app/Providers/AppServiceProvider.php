<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        @file_put_contents(
            base_path('debug_boot.txt'),
            now().' | boot() ran | sapi='.php_sapi_name().' | user='.get_current_user().PHP_EOL,
            FILE_APPEND,
        );

        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        Gate::after(function ($user, $ability, $result, $arguments) {
            $line = now().' | Gate::after | user='.($user?->email ?? 'guest')
                .' | ability='.$ability
                .' | result='.var_export($result, true)
                .' | args='.collect($arguments)->map(fn ($a) => is_object($a) ? get_class($a) : $a)->implode(',')
                .PHP_EOL;

            @file_put_contents(base_path('debug_gate.txt'), $line, FILE_APPEND);

            Log::info('DEBUG Gate::after', [
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'ability' => $ability,
                'result' => $result,
                'arguments' => collect($arguments)->map(fn ($a) => is_object($a) ? get_class($a) : $a)->all(),
            ]);
        });

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
