<?php

namespace App\Http\Livewire\AccountPayable;

use Livewire\Component;

class Edit extends Component
{
    public $data;
    public $account_id,$count_account,$bank_account_id,$receive_amount=0,$date,$outstanding_balance;
    public $kredit,$debit,$description,$coa_id,$no_voucher;
    public $total_kredit,$total_debit;
    public $is_readonly=false;
    protected $rules = [
        'coa_id.*' => 'required',
        'no_voucher'=>'required',
        'date'=>'required',
        'bank_account_id'=>'required'
    ];
    public function render()
    {
        return view('livewire.account-payable.edit');
    }

    public function mount($id)
    {
        $this->count_account[] = 1;
        $this->coa_id[] = "";
        $this->description[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;
        $this->data = \App\Models\Expenses::find($id);
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->no_voucher = $this->data->no_voucher;
        $this->date = $this->data->payment_date;
        $this->outstanding_balance = $this->data->outstanding_balance;
        $this->total_debit = $this->data->total_debit;
        $this->total_kredit = $this->data->total_kredit;
        if($this->data->status!=1){
            $this->is_readonly=true;
            $this->total_debit = $this->data->payment_amount;
        } 
        \LogActivity::add("Account Payable Edit {$id}");
    }

    public function addAccountForm()
    {
        $this->count_account[] = count($this->count_account);
        $this->coa_id[] = "";
        $this->description[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;
    }

    public function deleteAccountForm($key)
    {
        unset($this->count_account[$key]);
    }

    public function setNoVoucher($key)
    {
        if($this->no_voucher=="" and $this->is_readonly==false) $this->no_voucher = generate_no_voucher($this->coa_id[$key]);
    }
    
    public function sumDebit()
    {
        if(!$this->debit) return false;

        $this->total_debit=0;
        foreach($this->debit as $i){
            $this->total_debit += replace_idr($i);
        }
    }

    public function sumKredit() 
    {
        $this->total_kredit=0;
        foreach($this->kredit as $i){
            $this->total_kredit += replace_idr($i);
        }
    }

    public function save()
    {
        $this->validate();

        $statu = 0;
        if($this->total_kredit == $this->data->nominal) $status = 3; // complete 
        if($this->total_kredit < $this->data->nominal) $status = 2; // kurang bayar 
        if($this->total_kredit > $this->data->nominal) $status = 4; // lebih bayar 

        $this->data->payment_date = date('Y-m-d',strtotime($this->date));
        $this->data->no_voucher = $this->no_voucher;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->status = $status;
        $this->data->payment_amount = $this->total_kredit;
        $this->data->outstanding_balance = abs($this->outstanding_balance);
        $this->data->total_kredit = $this->total_kredit;
        $this->data->total_debit = $this->total_debit;
        $this->data->save();

        // save to journal
        foreach($this->coa_id as $key => $val){
            if($val=="") continue;
            $data  = new \App\Models\Journal();
            $data->transaction_id = $this->data->id;
            $data->transaction_table = 'expenses'; 
            $data->coa_id = $val;
            $data->no_voucher = $this->no_voucher;
            $data->date_journal = date('Y-m-d',strtotime($this->date));
            $data->debit = replace_idr(isset($this->debit[$key])?$this->debit[$key]:0);
            $data->kredit = replace_idr(isset($this->kredit[$key])?$this->kredit[$key]:0);
            $data->description = isset($this->description[$key])?$this->description[$key]:'';
            $data->bank_account_id = $this->bank_account_id;
            $data->saldo = (isset($this->debit[$key]) ? $this->debit[$key] : (isset($this->kredit[$key])?$this->kredit[$key] : 0));
            $data->save();
        }

        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('account-payable');
    }
}
