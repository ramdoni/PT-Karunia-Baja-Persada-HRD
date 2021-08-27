<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coa;

class JournalPenyesuaian extends Model
{
    use HasFactory;

    public function coa()
    {
        return $this->hasOne(Coa::class,'id','coa_id');
    }
}
