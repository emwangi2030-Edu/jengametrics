<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'date',
        'present',
    ];

    protected $casts = [
        'date' => 'date',
        'present' => 'boolean',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
