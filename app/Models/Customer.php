<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lead;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'entity_id',
    ];

    protected $primaryKey = 'entity_id';
    public $incrementing = false;
//    protected $attributes = [
//        'delayed' => false,//по умолчанию
//    ];
    public $alfaApi;

}

