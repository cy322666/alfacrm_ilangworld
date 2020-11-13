<?php

namespace App\Models;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'branch_id',
        'entity_id',
    ];

    protected $primaryKey = 'entity_id';
    public $incrementing  = false;
    public $alfaApi;

    public function searchAlfa(Lead $lead)
    {

    }

    public function createAlfa(Lead $lead)
    {

    }

    public function updateAlfa(Lead $lead)
    {

    }
}

