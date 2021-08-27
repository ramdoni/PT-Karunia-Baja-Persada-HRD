<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyariahEndorsement extends Model
{
    use HasFactory;
    protected $table = 'syariah_endorsement';
    public function parent()
    {
        return $this->hasOne(\App\Models\SyariahEndorsement::class,'id','parent_id');
    }
}
