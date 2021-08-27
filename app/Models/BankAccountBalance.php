<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountBalance extends Model
{
    use HasFactory;
    protected $table = 'bank_account_balance';
    public function bank_account()
    {
        return $this->belongsTo(\App\Models\BankAccount::class,'bank_account_id');
    }
}
