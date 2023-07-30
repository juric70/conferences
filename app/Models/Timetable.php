<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Timetable extends Model
{
    use HasFactory;
    protected $fillable = [
      'start_time',
      'end_time',
      'title',
      'address',
      'conference_room',
      'description',
      'available_seats',
      'conference_day_id',
      'user_id'
    ];

    public function conference_day() : BelongsTo
    {
        return $this->belongsTo(ConferenceDay::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
