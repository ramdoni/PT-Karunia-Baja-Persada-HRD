<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExpensePayment;
use App\Models\Policy;
use App\Models\ExpensePeserta;

class Expenses extends Model
{
    use HasFactory;
    public function policy()
    {
        return $this->belongsTo(Policy::class,'policy_id');
    }

    public function pesertas()
    {
        return $this->hasMany(ExpensePeserta::class,'expense_id');
    }

    public function payment_fee_base()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Fee Base');
    }

    public function payment_maintenance()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Maintenance');
    }
    public function payment_admin_agency()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Admin Agency');
    }
    public function payment_agen_penutup()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Agen Penutup');
    }
    public function payment_operasional_agency()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Operasional Agency');
    }
    public function payment_handling_fee_broker()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Handling Fee Broker');
    }
    public function payment_referal_fee()
    {
        return $this->hasOne(ExpensePayment::class,'expense_id')->where('transaction_type','Referal Fee');
    }
    public function bank_account()
    {
        return $this->hasOne('\App\Models\BankAccount','id','rekening_bank_id');
    }
    public function reinsurance()
    {
        return $this->hasOne('\App\Models\KonvenReinsurance','id','transaction_id');
    }
    public function komisi()
    {
        return $this->belongsTo(\App\Models\KonvenKomisi::class,'transaction_id');
    }
    public function uw()
    {
        return $this->hasOne('\App\Models\KonvenUnderwriting','id','transaction_id');
    }
    public function coa()
    {
        return $this->hasMany('\App\Models\ExpenseCoa','expense_id','id');
    }
    public function tax()
    {
        return $this->hasOne('\App\Models\SalesTax','id','tax_id');
    }
    public function claim()
    {
        return $this->hasOne('\App\Models\KonvenClaim','id','transaction_id');
    }
    public function memo()
    {
        return $this->hasOne('\App\Models\KonvenMemo','id','transaction_id');
    }
    public function from_bank_account()
    {
        return $this->belongsTo(\App\Models\BankAccount::class,'from_bank_account_id');
    }
}