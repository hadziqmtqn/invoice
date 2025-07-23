<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ZohoConfig extends Model
{
    protected $fillable = [
        'organization_id',
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

    public function zohoToken(): HasOne
    {
        return $this->hasOne(ZohoToken::class, 'zoho_config_id');
    }
}
