<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenUnderwritingCancelation extends Model
{
    use HasFactory;
    
    public function konven_memo_pos()
    {
        return $this->hasOne('\App\Models\KonvenMemo','id','konven_memo_pos_id');    
    }
}
