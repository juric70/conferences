<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'organization_id',
        'conference_id',
        'partner_type_id',
    ];

    public function partner_type():BelongsTo
    {
        return $this->belongsTo(PartnerType::class);
    }
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
