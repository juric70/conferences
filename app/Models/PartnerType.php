<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description'
    ];

    public function partner() : HasMany
    {
        return $this->hasMany(Partner::class);
    }
}
