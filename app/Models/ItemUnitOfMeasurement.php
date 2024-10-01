<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUnitOfMeasurement extends Model
{
    use HasFactory;

    protected $table = 'item_unit_of_measurements';

    protected $fillable = [
        'name',
        'category',
    ];

}
