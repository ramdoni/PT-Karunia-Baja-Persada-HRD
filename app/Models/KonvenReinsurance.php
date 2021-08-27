<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenReinsurance extends Model
{
    use HasFactory;
    protected $table = 'konven_reinsurance';

    public function uw()
    {
        return $this->hasOne('\App\Models\KonvenUnderwriting','id','transaction_id');
    }
    public function parent()
    {
        return $this->hasOne(\App\Models\KonvenReinsurance::class,'id','parent_id');
    }
}
