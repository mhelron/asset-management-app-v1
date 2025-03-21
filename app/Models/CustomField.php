<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'type', 
        'text_type', 
        'is_required', 
        'options', 
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array', // Automatically convert JSON to array when accessed
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_custom_field');
    }
}
