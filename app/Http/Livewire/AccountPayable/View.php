<?php

namespace App\Http\Livewire\AccountPayable;

use Livewire\Component;

class View extends Component
{
    public $data,$date,$bank_account_id,$no_voucher;
    public $total_debit,$total_kredit;
    public function render()
    {
        return view('livewire.account-payable.view');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->date = $this->data->payment_date;
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->no_voucher = $this->data->no_voucher;
    }
}
