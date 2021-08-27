<?php

namespace App\Http\Livewire\AccountPayable;

use Livewire\Component;

class Insert extends Component
{
    public $coa_id,$date_journal,$no_voucher,$description,$nominal;
    public function render()
    {
        return view('livewire.account-payable.insert');
    }
    public function mount()
    {
        \LogActivity::add("Account Payable Insert");
    }
    public function save()
    {
        $this->validate([
            'coa_id'=>'required',
            'date_journal'=>'required',
            'nominal'=>'required',
            'no_voucher'=>'required'
        ]);
        $data = new \App\Models\Journal();
        $data->coa_id = $this->coa_id;
        $data->date_journal = date('Y-m-d',strtotime($this->date_journal));
        $data->debit = $this->nominal;
        $data->no_voucher = $this->no_voucher;
        $data->description = $this->description;
        $data->type = 1; // Debit / Payable
        $data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("Account Payable Save {$data->id}");
        
        return redirect()->to('account-payable');
    }

    public function setNoVoucher()
    {
        $this->no_voucher = generate_no_voucher($this->coa_id);
    }
}