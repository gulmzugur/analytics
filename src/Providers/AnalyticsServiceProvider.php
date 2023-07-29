<?php

namespace Squirtle\Analytics\Providers;

use Illuminate\Support\ServiceProvider;
use Squirtle\Analytics\Analytics;
use Squirtle\Analytics\AnalyticsClient;
use Squirtle\Analytics\AnalyticsClientFactory;
use Squirtle\Analytics\Exceptions\InvalidConfiguration;

class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(AnalyticsClient::class, function () {
            return AnalyticsClientFactory::createForConfig(config('analytics'));
        });

        $this->app->bind(Analytics::class, function () {
            if (empty(config('analytics.view_id'))) {
                throw InvalidConfiguration::viewIdNotSpecified();
            }

            if (!config('analytics.account_credentials')) {
                throw InvalidConfiguration::credentialsIsNotValid();
            }

            return new Analytics(
                $this->app->make(AnalyticsClient::class),
                config('analytics.view_id')
            );
        });

    }
}