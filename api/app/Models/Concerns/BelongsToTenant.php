<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Tenancy;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (! $model->tenant_id && tenancy()->initialized) {
                $model->tenant_id = tenant('id');
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (tenancy()->initialized) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', tenant('id'));
            }
        });
    }
}
