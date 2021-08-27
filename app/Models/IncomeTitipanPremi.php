<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTitipanPremi extends Model
{
    use HasFactory;

    protected $table = 'income_titipan_premi';
    protected $fillable = ['income_premium_id','income_titipan_id','nominal','transaction_type'];
    
    public function titipan()
    {
        return $this->belongsTo(\App\Models\Income::class,'income_titipan_id');
    }
    
    public function premi()
    {
        return $this->belongsTo(\App\Models\Income::class,'income_premium_id');
    }
}
