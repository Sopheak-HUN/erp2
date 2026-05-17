<?php

namespace App\Models\Inventory;

use App\Models\Concerns\BelongsToTenant;
use App\Models\Concerns\HasUuidV7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use BelongsToTenant, HasUuidV7, SoftDeletes;

    protected $fillable = [
        'sku',
        'name_en',
        'name_km',
        'description',
        'category_id',
        'unit_of_measure',
        'cost_price',        // minor units
        'selling_price',     // minor units
        'currency',
        'vat_applicable',
        'is_service',
        'is_active',
        'reorder_level',
        'current_stock',
    ];

    protected $casts = [
        'cost_price' => 'integer',
        'selling_price' => 'integer',
        'vat_applicable' => 'boolean',
        'is_service' => 'boolean',
        'is_active' => 'boolean',
        'reorder_level' => 'integer',
        'current_stock' => 'integer',
    ];
}
