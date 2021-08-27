<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenMemo extends Model
{
    use HasFactory;
    protected $table="konven_memo_pos";
    public function uw()
    {
        return $this->belongsTo(\App\Models\KonvenUnderwriting::class,'konven_underwriting_id');
    }
    public function parent()
    {
        return $this->hasOne(\App\Models\KonvenMemo::class,'id','parent_id');
    }
}
