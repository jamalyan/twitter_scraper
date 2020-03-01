<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SearchValue extends Model
{
    protected $fillable = [
        'name',
        'last_max',
    ];

    /**
     * @return HasMany
     */
    public function tweets()
    {
        return $this->hasMany(Tweet::class, 'value_id', 'id');
    }
}
