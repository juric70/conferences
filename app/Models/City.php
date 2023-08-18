<?php

namespace App\Models;

use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'zip_code',
        'country_id',
    ];

    public function country() : BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function conference() : HasMany
    {
        return $this->hasMany(Conference::class);
    }
    public function organization():HasMany
    {
        return $this->hasMany(Organization::class);
    }
    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

}
