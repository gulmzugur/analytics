<?php
return [
    /*
     * The view id of which you want to display data.
    */
    'view_id' => 'YOUR_VIEW_ID',

    /*
     * The amount of minutes the Google API responses will be cached.
     * If you set this to zero, the responses won't be cached at all.
     */
    'cache_lifetime' => 60 * 24,

    /*
     * Here you may configure the "store" that the underlying Google_Client will
     * use to store its data.  You may also add extra parameters that will
     * be passed on setCacheConfig (see docs for google-api-php-client).
     *
     * Optional parameters: "lifetime", "prefix"
     */
    'cache' => [
        'store' => 'file',
    ]
];