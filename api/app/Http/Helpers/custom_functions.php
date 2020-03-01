<?php

use App\Models\SearchValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Thujohn\Twitter\Facades\Twitter;

if (!function_exists('get_rate_limit')) {
    /**
     * @return int
     */
    function get_rate_limit()
    {
        try {
            if (!Cache::has('rate_time') || (Cache::get('rate_time') <= Carbon::now()->subMinutes(15))) {
                Cache::forever('rate_time', Carbon::now());
                Cache::forever('rate_limit', Twitter::getAppRateLimit()->resources->search->{'/search/tweets'}->remaining);
            }
        } catch (Exception $exception) {
            Log::channel('scraper')->error($exception->getMessage());
        }

        Cache::forever('rate_limit', (Cache::get('rate_limit', 0) - 1));

        return Cache::get('rate_limit', 0);
    }
}

if (!function_exists('get_twitter_interval')) {
    /**
     * @return int
     */
    function get_twitter_interval()
    {
        try {
            $limit_per_minute = Twitter::getAppRateLimit()->resources->search->{'/search/tweets'}->limit / 15;
        } catch (Exception $exception) {
            Log::channel('scraper')->error($exception->getMessage());
            $limit_per_minute = 180 / 15;
        }
        $count_for_sec = 60 / $limit_per_minute;

        return SearchValue::query()->count() * $count_for_sec;
    }
}


if (!function_exists('twitter_date_to_carbon')) {
    /**
     * @param $date
     * @return Carbon
     */
    function twitter_date_to_carbon($date)
    {
        return Carbon::createFromFormat('D M d H:i:s O Y', $date);
    }
}