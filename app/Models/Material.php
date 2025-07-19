<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getTotalAmountAttribute()
    {
        return (float) $this->unit_price * (float) $this->quantity_in_stock;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function stockUsages()
    {
        return $this->hasMany(StockUsage::class);
    }
}
