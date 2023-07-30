<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrganizationsOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'kind',
        'publishable_conferences',
        'price',
        'description'
    ];

    public function organizations() : BelongsToMany
    {
        return $this
            ->belongsToMany(Organization::class, 'offer_organizations', 'organizations_offer_id', 'organization_id')
            ->withPivot( 'paid', 'payment_date')
            ->withTimestamps();
    }
}
