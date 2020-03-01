<?php

namespace App\Http\Helpers;

use App\Models\SearchValue;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Thujohn\Twitter\Facades\Twitter;

trait TwitterScraper
{

    /**
     * @param SearchValue $search_value
     * @param int $count
     * @return void
     */
    public function searchAndScrap(SearchValue $search_value, $count = 100)
    {
        $data = [
            'q' => '"' . $search_value->name . '"',
            'count' => $count,
        ];

        if ($search_value->last_max) $data['since_id'] = $search_value->last_max;

        if (get_rate_limit() > 0) {
            try {
                $response = Twitter::getSearch($data);
                if (isset($response->statuses) && !empty($response->statuses)) {
                    $tweet_data = $this->generateTweetData($response->statuses, $search_value->id);

                    DB::transaction(function () use ($search_value, $response, $tweet_data) {
                        DB::table('search_values')
                            ->where('name', '=', $search_value->name)
                            ->update(['last_max' => $response->search_metadata->max_id]);

                        DB::table('tweets')->insertOrIgnore($tweet_data);
                    });
                }
            } catch (Exception $exception) {
                Log::channel('scraper')->error($exception->getMessage());
            }
        }
    }

    /**
     * @param array $statuses
     * @param $id
     * @return array
     */
    private function generateTweetData(array $statuses, $id)
    {
        $tweet_data = [];
        $now = Carbon::now();

        foreach ($statuses as $status) {
            $tweet_data[] = [
                'value_id' => $id,
                'tweet_id' => $status->id,
                'tweet_date' => twitter_date_to_carbon($status->created_at),
                'tweet_user_id' => $status->user ? $status->user->id : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $tweet_data;
    }
}