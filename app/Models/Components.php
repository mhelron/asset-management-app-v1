<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Components extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'component_name',
        'category',
        'serial_no',
        'model_no',
        'manufacturer',
        'users_id',
        'date_purchased',
        'purchased_from',
        'log_note',
        'inventory_id'
    ];

    protected $dates = ['date_purchased'];

    protected $casts = [
        'date_purchased' => 'date'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}