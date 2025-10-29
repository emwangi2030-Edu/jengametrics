<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'project_id',
        'parent_id',
    ];

    public function sections()
    {
        return $this->hasMany(BqSection::class);
    }

    public function items()
    {
        return $this->hasMany(BqItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
