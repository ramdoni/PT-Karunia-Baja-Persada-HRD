<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JournalPenyesuaian;

class Journal extends Model
{
    use HasFactory;

    public function coa()
    {
        return $this->hasOne('\App\Models\Coa','id','coa_id');
    }

    public function bank()
    {
        return $this->hasOne('\App\Models\BankAccount','id','bank_account_id');
    }

    public function code_cashflow()
    {
        return $this->hasOne('\App\Models\CodeCashflow','id','code_cashflow_id');
    }
    
    public function penyesuaian()
    {
        return $this->hasMany(JournalPenyesuaian::class,'journal_no_voucher','no_voucher');
    }
}
