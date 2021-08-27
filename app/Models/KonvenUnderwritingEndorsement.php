<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenUnderwritingEndorsement extends Model
{
    use HasFactory;

    public function konven_memo_pos()
    {
        return $this->belongsTo(\App\Models\KonvenMemo::class,'konven_memo_pos_id');    
    }
}
