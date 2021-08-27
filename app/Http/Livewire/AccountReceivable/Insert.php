<?php

namespace App\Http\Livewire\AccountReceivable;

use Livewire\Component;

class Insert extends Component
{
    public $coa_id,$date_journal,$no_voucher,$description,$nominal;
    public function render()
    {
        return view('livewire.account-receivable.insert');
    }

    public function save()
    {
        $this->validate([
            'coa_id'=>'required',
            'date_journal'=>'required',
            'no_voucher'=>'required',
            'nominal'=>'required'
        ]);
        $data = new \App\Models\Journal();
        $data->coa_id = $this->coa_id;
        $data->date_journal = date('Y-m-d',strtotime($this->date_journal));
        $data->debit = $this->nominal;
        $data->no_voucher = $this->no_voucher;
        $data->description = $this->description;
        $data->type = 2; // Kredit / Receivable
        $data->save();
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('account-receivable');
    }

    public function setNoVoucher()
    {
        $this->no_voucher = generate_no_voucher($this->coa_id);
    }
}
