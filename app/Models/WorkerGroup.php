<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'created_by',
        'name',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'group_worker')->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(LabourTask::class);
    }
}

