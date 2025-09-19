<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_no',
        'bom_item_id',
        'extra_material_name',
        'extra_unit',
        'quantity_requested',
        'section_id',
        'status',
        'requested_by',
        'approved_by',
        'requested_at',
        'approved_at',
    ];

    public function bomItem()
    {
        return $this->belongsTo(BomItem::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
