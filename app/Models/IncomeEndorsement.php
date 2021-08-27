<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeEndorsement extends Model
{
    use HasFactory;

    public function syariah()
    {
        return $this->hasOne(\App\Models\SyariahEndorsement::class,'id','transaction_id');    
    }
    public function konven()
    {
        return $this->hasOne(\App\Models\KonvenMemo::class,'id','transaction_id','id');    
    }
}
