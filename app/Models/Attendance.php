<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'present',
        'arrival',
        'departure',
        'user_id',
        'timetable_id',
        'conference_role_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function conference_role()
    {
        return $this->belongsTo(ConferenceRole::class);
    }
}
