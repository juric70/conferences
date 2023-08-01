<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'conference_day_id',
      'users_offer_id',
      'price',
      'paid',
      'payment_date'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conferenceDay() : BelongsTo
    {
        return $this->belongsTo(ConferenceDay::class);
    }

    public function usersOffer() : BelongsTo
    {
        return $this->belongsTo(UsersOffer::class);
    }
}
