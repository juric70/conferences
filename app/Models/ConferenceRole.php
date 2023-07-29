<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description'
    ];
}
