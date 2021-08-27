<?php

namespace App\Http\Livewire\AccountReceivable;

use Livewire\Component;

class View extends Component
{
    public $data;
    public $account_id,$count_account,$bank_account_id,$receive_amount=0,$date,$outstanding_balance;
    public $kredit,$debit,$description,$coa_id,$no_voucher;
    public $total_kredit,$total_debit;
    public function render()
    {
        return view('livewire.account-receivable.view');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Income::find($id);
        $this->date = $this->data->payment_date;
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->no_voucher = $this->data->no_voucher;
    }
}
