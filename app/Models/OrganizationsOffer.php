<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationsOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'kind',
        'publishable_conferences',
        'price',
        'description'
    ];
}
