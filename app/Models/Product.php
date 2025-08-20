<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'barcode',
        'nafdac_number',
        'batch_number',
        'expiry_date',
        'manufacture_date',
        'manufacturer',
        'country_of_origin',
        'image_url',
        'status',
        'description',
        'active_ingredient',
        'dosage_form',
        'strength',
        'packaging',
        'prescription_required',
        'volume_or_weight',
        'nutritional_info',
        'flavour',
        'storage_instructions',
        'added_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'manufacture_date' => 'date',
        'nutritional_info' => 'array',
        'prescription_required' => 'boolean',
    ];
}