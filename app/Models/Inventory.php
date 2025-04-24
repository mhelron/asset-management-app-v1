<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_name', 
        'category_id',
        'department_id',
        'user_id',
        'asset_tag',
        'serial_no',
        'model_no',
        'manufacturer',
        'date_purchased',
        'purchased_from',
        'image_path',
        'log_note',
        'custom_fields', 
        'status'
    ];

    protected $casts = [
        'custom_fields' => 'array',
    ];

    public function getCustomFieldsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

}