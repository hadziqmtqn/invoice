<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'organization_id',
    ];

    public function zohoConfig(): HasOne
    {
        return $this->hasOne(ZohoConfig::class, 'organization_id');
    }

    // TODO Scope
    #[Scope]
    protected function filter(Builder $query, $request): Builder
    {
        $search = $request['search'] ?? null;

        return $query->when($search, fn($query) => $query->whereLike('name', '%' . $search . '%'));
    }
}
