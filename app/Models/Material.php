<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'bom_item_id', 'name', 'description', 'unit_price', 'unit_of_measure',
        'quantity_purchased', 'quantity_in_stock', 'variance', 'supplier_id', 'supplier_contact',
        'document', 'project_id'
    ];

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
