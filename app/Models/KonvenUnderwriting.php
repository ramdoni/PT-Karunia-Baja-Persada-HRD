<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonvenUnderwriting extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'konven_underwriting';

    public function coa()
    {
        return $this->hasMany('\App\Models\KonvenUnderwritingCoa','konven_underwriting_id','id')->orderBy('ordering','ASC');
    }
    public function coaDesc()
    {
        return $this->hasMany('\App\Models\KonvenUnderwritingCoa','konven_underwriting_id','id')->orderBy('ordering','DESC');  
    }
    public function cancelation()
    {
        return $this->hasMany('\App\Models\KonvenUnderwritingCancelation','konven_underwriting_id','id');
    }
    public function parent()
    {
        return $this->hasOne(\App\Models\KonvenUnderwriting::class,'id','parent_id');
    }
}
