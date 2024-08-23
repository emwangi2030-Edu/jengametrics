<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_info',
        'created_at',
    ];

    // A supplier can have many materials
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    // Retrieve the material name, contact info, and date
    public function getMaterialDetails()
    {
        return $this->materials()->select('name', 'contact_info', 'created_at')->get();
    }
}
