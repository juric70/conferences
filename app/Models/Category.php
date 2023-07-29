<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'color'
    ];
    public function conference_days():BelongsToMany
    {
        return $this->belongsToMany(ConferenceDay::class, 'categories_conference_days', 'category_id', 'conference_day_id');
    }
}
