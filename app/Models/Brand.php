<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the country this brand belongs to.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Override the route key to use UUID.
     * Note: Route model binding is NOT used for security scoping —
     * the BrandService resolves brands manually via UUID + country scope.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
