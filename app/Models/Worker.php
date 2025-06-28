<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 
        'id_number', 
        'job_category', 
        'work_type', 
        'phone', 
        'email',
        'payment_amount', 
        'payment_frequency',
        'details', 
        'project_id' 
    ];

    // Define the relationship to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}

