<?php

namespace App\Http\Livewire\ExpenseEndorsement;

use Livewire\Component;

class Detail extends Component
{
    public $data,$no_voucher,$client,$recipient,$reference_type,$reference_no,$reference_date,$description,$outstanding_balance,$tax_id,$payment_amount=0,$bank_account_id;
    public $payment_date,$tax_amount,$total_payment_amount,$is_readonly=false,$is_finish=false,$paid_premi=1,$paid_premi_id;
    public $bank_charges,$from_bank_account_id;
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    protected $rules = [
                            'bank_account_id'=>'required',
                            'payment_amount'=>'required',
                            'from_bank_account_id' => 'required'
                        ];
    public function render()
    {
        return view('livewire.expense-endorsement.detail');
    } 
    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->payment_date = $this->data->payment_date?$this->data->payment_date : date('Y-m-d');
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->payment_amount = $this->data->payment_amount;
        $this->total_payment_amount = $this->data->total_payment_amount;
        $premi = \App\Models\Income::where('transaction_id',$this->data->uw->id)->where('transaction_table','konven_underwriting')->first();
        if($premi){
            $this->paid_premi = $premi->status;
            $this->paid_premi_id =$premi->id;
            if($premi->status!=2) $this->is_readonly = true;
        }
        if($this->payment_amount =="") $this->payment_amount=$this->data->nominal;
        if($this->data->status==2) $this->is_finish = true;
        \LogActivity::add("Expense Endorsement Detail {$this->data->id}");
    }
    public function updated($propertiName)
    {
        if($this->bank_charges) $this->payment_amount = $this->data->nominal + replace_idr($this->bank_charges);
        $this->emit('init-form');
    }
    public function emitAddBank($id)
    {
        $this->bank_account_id = $id;
        $this->emit('init-form');
    }
    public function save()
    {
        $this->validate();

        $this->data->payment_amount = $this->payment_amount;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->payment_date = $this->payment_date;
        $this->data->status = 2;
        $this->data->save();
        \LogActivity::add("Expense Endorsement Submit {$this->data->id}");
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('expense.endorsement');
    }
}