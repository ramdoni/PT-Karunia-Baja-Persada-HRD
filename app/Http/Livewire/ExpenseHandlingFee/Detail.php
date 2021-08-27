<?php

namespace App\Http\Livewire\ExpenseHandlingFee;

use Livewire\Component;

class Detail extends Component
{
    public $data,$no_voucher,$client,$recipient,$reference_type,$reference_no,$reference_date,$description,$outstanding_balance,$tax_id,$payment_amount=0,$bank_account_id,$from_bank_account_id;
    public $payment_date,$tax_amount,$total_payment_amount,$is_readonly=false,$payment_pph=true,$payment_ppn=true,$bank_charges,$paid_premi=1,$paid_premi_id;
    protected $rules = 
                        [
                            'payment_amount'=>'required|not_in:0',
                            'from_bank_account_id'=>'required'
                        ];
    public function render()
    {
        return view('livewire.expense-handling-fee.detail');
    }
    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->payment_date = $this->data->payment_date?$this->data->payment_date : date('Y-m-d');
        $this->from_bank_account_id = $this->data->from_bank_account_id;
        $this->bank_charges = $this->data->bank_charges;
        $this->payment_amount = $this->data->uw->jumlah_pph + $this->data->uw->jumlah_ppn;
        $premi = \App\Models\Income::where('transaction_id',$this->data->uw->id)->where('transaction_table','konven_underwriting')->first();
        if($premi){
            $this->paid_premi = $premi->status;
            $this->paid_premi_id =$premi->id;
        }
        if($this->data->status==2) $this->is_readonly = true;
        
        \LogActivity::add("Expense Handling Fee Detail {$this->data->id}");
    }
    public function calculate_()
    {
        $this->payment_amount = 0;
        if($this->payment_pph) $this->payment_amount += $this->data->uw->jumlah_pph;
        if($this->payment_ppn) $this->payment_amount += $this->data->uw->jumlah_ppn;
        $this->payment_amount += replace_idr($this->bank_charges);
    }
    public function save()
    {
        if($this->data->status==2) return false;
        $this->validate();
        $this->data->status = 2;
        $this->data->payment_amount = $this->payment_amount;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->from_bank_account_id = $this->from_bank_account_id;
        $this->data->payment_date = $this->payment_date;
        $this->data->bank_charges = replace_idr($this->bank_charges);
        $this->data->save();
        \LogActivity::add("Expense Handling Fee Submit {$this->data->id}");
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('expense-handling-fee');
    }
}