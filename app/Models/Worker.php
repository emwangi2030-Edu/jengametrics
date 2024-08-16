<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'id_number', 'job_category', 'work_type', 
        'phone', 'email', 'details'
    ];
}
