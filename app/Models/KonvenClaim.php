<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenClaim extends Model
{
    use HasFactory;
    protected $table = 'konven_claim';
    public function uw()
    {
        return $this->belongsTo(\App\Models\KonvenUnderwriting::class,'konven_underwriting_id');
    }
}
