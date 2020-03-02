<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tweet;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TweetController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        /** @var Tweet|Collection $tweets */
        $tweets = Tweet::query()
            ->select(DB::raw('DATE(tweets.tweet_date) as date'), DB::raw('count(tweets.id) as tweets_count'), 'name')
            ->join('search_values', 'tweets.value_id', '=', 'search_values.id')
            ->where('tweets.tweet_date', '>=', Carbon::now()->subWeek())
            ->groupBy('name', 'date')
            ->get()
            ->groupBy('name');

        $dates = [];
        $data_set = [];

        foreach ($tweets as $label => $tweet) {
            $new = [];
            $new['label'] = $label;
            foreach ($tweet as $key => $item) {
                $new['data'][] = $item->tweets_count;
                $dates[$item->date] = $key;
            }
            $data_set[] = $new;
        }
        $dates = array_flip($dates);

        return response()->json(['labels' => $dates, 'sets' => $data_set], 200);
    }
}
