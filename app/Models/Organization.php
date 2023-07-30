<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
      'name',
      'address',
      'description',
      'approved',
      'city_id',
      'organization_type_id',
      'user_id'
    ];

    public function organization_type() : BelongsTo
    {
        return $this->belongsTo(OrganizationType::class);
    }

    public function city() : BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner() : HasMany
    {
        return $this->hasMany(Partner::class);
    }
}
