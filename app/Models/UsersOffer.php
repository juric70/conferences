<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsersOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'kind',
        'code',
        'number_of_days',
        'description',
        'price',
        'conference_id'
    ];

    public function conference () : BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function tickets() : HasMany
    {
        return $this->hasMany(Ticket::class);
    }

}
