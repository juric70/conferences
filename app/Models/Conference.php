<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conference extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'starting_date',
        'ending_date',
        'user_id',
        'city_id',

    ];
    public function city():BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function conference_day() : HasMany
    {
        return $this->hasMany(ConferenceDay::class);
    }

    public function partner() : HasMany
    {
        return $this->hasMany(Partner::class);
    }
}
