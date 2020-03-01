<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tweet extends Model
{
    protected $fillable = [
        'value_id',
        'tweet_id',
        'tweet_date',
        'tweet_user_id',
    ];

    protected $dates = [
        'tweet_date'
    ];

    /**
     * @param $value
     */
    public function setTweetDateAttribute($value)
    {
        $this->attributes['tweet_date'] = twitter_date_to_carbon($value);
    }

    /**
     * @return BelongsTo
     */
    public function search_value()
    {
        return $this->belongsTo(SearchValue::class, 'value_id', 'id');
    }
}
