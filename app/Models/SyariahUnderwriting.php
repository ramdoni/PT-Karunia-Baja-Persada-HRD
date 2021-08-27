<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyariahUnderwriting extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function parent()
    {
        return $this->hasOne(\App\Models\SyariahUnderwriting::class,'id','parent_id');
    }
}
