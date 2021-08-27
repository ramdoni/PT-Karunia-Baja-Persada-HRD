<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GeneralLedger;

class GeneralLedgerHistory extends Model
{
    use HasFactory;

    protected $table = 'general_ledger_history';

    public function gl()
    {
        return $this->hasOne(GeneralLedger::class,'id','general_ledger_id');
    }
}
