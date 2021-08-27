<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenUnderwritingCoa extends Model
{
    use HasFactory;

    protected $table = 'konven_underwriting_coa';

    public function coa()
    {
        return $this->hasOne('\App\Models\Coas','id','coa_id');
    }
}
