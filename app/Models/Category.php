<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory, SoftDeletes; 

    protected $table = 'categories';

    protected $fillable = [
        'category',
        'desc',
        'status',
        'custom_fields',
    ];

    protected $casts = [
        'custom_fields' => 'array',
    ];

    public function inventory() {
        return $this->hasMany(Inventory::class);
    }

    public function customFields(){
        return $this->belongsToMany(CustomField::class, 'category_custom_field');
    }

}
