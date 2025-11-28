<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name', 
        'id_number', 
        'job_category', 
        'work_type', 
        'phone', 
        'email',
        'picture',
        'payment_amount', 
        'payment_frequency',
        'mode_of_payment',
        'bank_name', 
        'bank_account',
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

     public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
