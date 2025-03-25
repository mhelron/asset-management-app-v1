<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Accessory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'accessory_name',
        'category',
        'department',
        'serial_no',
        'model_no',
        'manufacturer',
        'assigned',
        'date_purchased',
        'purchased_from',
        'log_note'
    ];

    protected $dates = ['date_purchased'];

    protected $casts = [
        'date_purchased' => 'date'
    ];
}
