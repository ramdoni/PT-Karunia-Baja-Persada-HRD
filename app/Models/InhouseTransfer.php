<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseTransfer extends Model
{
    use HasFactory;
    protected $table = 'inhouse_transfer';
    public function from_bank_account()
    {
        return $this->belongsTo(\App\Models\BankAccount::class,'from_bank_account_id');
    }
    public function to_bank_account()
    {
        return $this->belongsTo(\App\Models\BankAccount::class,'to_bank_account_id');
    }
}
