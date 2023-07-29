<?php

namespace Squirtle\Analytics;

use Squirtle\Analytics\Services\AnalyticsService;
use Illuminate\Contracts\Cache\Repository;
use DateTimeInterface;

class AnalyticsClient
{
    /**
     * @var AnalyticsService
     */
    protected AnalyticsService $service;

    /**
     * @var Repository
     */
    protected Repository $cache;

    /**
     * @var int
     */
    protected int $cacheLifeTimeInMinutes = 0;

    /**
     * AnalyticsClient constructor.
     * @param AnalyticsService $service
     * @param Repository $cache
     */
    public function __construct(AnalyticsService $service, Repository $cache)
    {
        $this->service = $service;
        $this->cache = $cache;
    }

    /**
     * Set the cache time.
     *
     * @param int $cacheLifeTimeInMinutes
     *
     * @return AnalyticsClient
     */
    public function setCacheLifeTimeInMinutes(int $cacheLifeTimeInMinutes): AnalyticsClient
    {
        $this->cacheLifeTimeInMinutes = $cacheLifeTimeInMinutes * 60;

        return $this;
    }

    /**
     * Query the Analytics Service with given parameters.
     *
     * @param string $viewId
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @param string $metrics
     * @param array $others
     *
     * @return array|null
     */
    public function performQuery(
        string            $viewId,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate,
        string            $metrics,
        array             $others = []
    ): ?array
    {
        $cacheName = $this->determineCacheName(func_get_args());

        if ($this->cacheLifeTimeInMinutes == 0) {
            $this->cache->forget($cacheName);
        }

        return $this->cache->remember($cacheName, $this->cacheLifeTimeInMinutes,
            function () use ($viewId, $startDate, $endDate, $metrics, $others) {
                $result = $this->service->data_ga->get(
                    'ga:' . $viewId,
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                    $metrics,
                    $others
                );

                while ($nextLink = $result->getNextLink()) {
                    if (isset($others['max-results']) && count($result->rows) >= $others['max-results']) {
                        break;
                    }

                    $options = [];

                    parse_str(substr($nextLink, strpos($nextLink, '?') + 1), $options);

                    $response = $this->service->data_ga->call('get', [$options], 'Google_Service_Analytics_GaData');

                    if ($response->rows) {
                        $result->rows = array_merge($result->rows, $response->rows);
                    }

                    $result->nextLink = $response->nextLink;
                }

                return $result;
            });
    }


    /**
     * Determine the cache name for the set of query properties given.
     *
     * @param array $properties
     * @return string
     */
    protected function determineCacheName(array $properties): string
    {
        return 'analytics.' . md5(serialize($properties));
    }

    /**
     *
     * @return AnalyticsService
     */
    public function getAnalyticsService(): AnalyticsService
    {
        return $this->service;
    }
}