<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id', 'product_id', 'bom_item_id', 'name', 'description', 'unit_price', 'unit_of_measure',
        'quantity_purchased', 'quantity_in_stock', 'variance', 'requisitioned_quantity', 'supplier_id', 'supplier_contact',
        'document', 'project_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity_purchased' => 'decimal:2',
        'quantity_in_stock' => 'decimal:2',
        'variance' => 'decimal:2',
        'requisitioned_quantity' => 'decimal:2',
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

    public function itemMaterial()
    {
        return $this->belongsTo(ItemMaterial::class, 'bom_item_id');
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
