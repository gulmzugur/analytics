<?php

namespace Squirtle\Analytics;


use Illuminate\Support\Facades\Cache;
use Squirtle\Analytics\Services\AnalyticsService;
use Symfony\Component\Cache\Adapter\Psr16Adapter;
use Illuminate\Contracts\Cache\Repository;

class AnalyticsClientFactory
{
    /**
     * @param GoogleClient $client
     * @param array $config
     */
    protected static function configureCache(GoogleClient $client, array $config)
    {
        $config = collect($config);

        $store = Cache::store($config->get('store'));

        $cache = new Psr16Adapter($store);

        $client->setCache($cache);

        $client->setCacheConfig($config->except('store')->toArray());
    }

    /**
     * @param array $config
     * @return GoogleClient
     */
    public static function createAuthenticatedGoogleClient(array $config): GoogleClient
    {
        $client = new GoogleClient;

        $client->setScopes([
            AnalyticsService::ANALYTICS_READONLY,
        ]);

        $client->setAuthenticationConfiguration(config('analytics.account_credentials'));

        self::configureCache($client, $config['cache']);

        return $client;
    }

    /**
     * @param array $config
     * @param AnalyticsService $service
     * @return AnalyticsClient
     */
    protected static function createAnalyticsClient(array $config, AnalyticsService $service): AnalyticsClient
    {
        $client = new AnalyticsClient($service, app(Repository::class));

        $client->setCacheLifeTimeInMinutes($config['cache_lifetime']);

        return $client;
    }

    /**
     * @param array $config
     * @return AnalyticsClient
     */
    public static function createForConfig(array $config): AnalyticsClient
    {
        $authenticatedClient = self::createAuthenticatedGoogleClient($config);

        $googleService = new AnalyticsService($authenticatedClient);

        return self::createAnalyticsClient($config, $googleService);
    }
}