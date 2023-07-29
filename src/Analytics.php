<?php

namespace Squirtle\Analytics;

use Squirtle\Analytics\Services\AnalyticsService;

class Analytics
{
    /**
     * @var AnalyticsClient
     */
    protected AnalyticsClient $client;

    /**
     * @var string
     */
    protected string $viewId;

    /**
     * @param AnalyticsClient $client
     * @param string $viewId
     */
    public function __construct(AnalyticsClient $client, string $viewId)
    {
        $this->client = $client;

        $this->viewId = $viewId;
    }

    /**
     * @return string
     */
    public function getViewId(): string
    {
        return $this->viewId;
    }

    /**
     * @param string $viewId
     *
     * @return $this
     */
    public function setViewId(string $viewId): Analytics
    {
        $this->viewId = $viewId;

        return $this;
    }


    /*
    * Get the underlying Google_Service_Analytics object. You can use this
    * to basically call anything on the Google Analytics API.
    */
    public function getAnalyticsService(): AnalyticsService
    {
        return $this->client->getAnalyticsService();
    }
}