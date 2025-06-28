<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZohoConfig extends Model
{
    protected $fillable = [
        'organization_id',
        'grant_type',
        'code',
        'client_id',
        'client_secret',
        'redirect_url',
        'refresh_token',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
