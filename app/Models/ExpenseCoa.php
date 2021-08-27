<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCoa extends Model
{
    use HasFactory;
    
    public function coa()
    {
        return $this->hasOne('\App\Models\Coas','id','coa_id');
    }
}
