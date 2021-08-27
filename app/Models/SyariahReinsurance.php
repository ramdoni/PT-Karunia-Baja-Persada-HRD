<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyariahReinsurance extends Model
{
    use HasFactory;
    protected $table='syariah_reinsurance';
    public function parent()
    {
        return $this->hasOne(\App\Models\SyariahReinsurance::class,'id','parent_id');
    }
}
