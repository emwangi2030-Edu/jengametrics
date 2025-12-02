<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'amount',
        'payment_date',
        'period_start',
        'period_end',
        'project_id',
    ];

    public function worker()                                                                                                                          
    {                                                                                                                                                 
        return $this->belongsTo(Worker::class)->withTrashed();                                                                                        
    }

    protected $casts = [
        'payment_date' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];
}
