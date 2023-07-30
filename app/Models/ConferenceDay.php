<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConferenceDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'day_number',
        'price',
        'date',
        'conference_id'
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class , 'categories_conference_days', 'conference_day_id', 'category_id');
    }
    public function timetable() : HasMany
    {
        return $this->hasMany(Timetable::class);
    }
}
