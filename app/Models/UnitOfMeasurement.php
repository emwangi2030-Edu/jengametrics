<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    use HasFactory;

    protected $table = 'units_of_measurement';

    protected $fillable = [
        'name',
        'type',
    ];

}