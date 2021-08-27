<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CoaGroup;

class GeneralLedger extends Model
{
    use HasFactory;

    protected $table = 'general_ledger';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function coa_group()
    {
        return $this->belongsTo(CoaGroup::class,'coa_group_id');
    }
}
