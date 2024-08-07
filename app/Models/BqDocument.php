<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BqDocument extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_id'];

    public function sections()
    {
        return $this->hasMany(BqSection::class);
    }

    public function items()
    {
        return $this->hasMany(BqItem::class);
    }
}