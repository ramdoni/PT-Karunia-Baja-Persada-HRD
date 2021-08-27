<?php

namespace App\Http\Livewire\Operation;

use Livewire\Component;

class ExpenseEdit extends Component
{
    public $no_voucher,$recipient,$reference_type,$reference_no,$reference_date,$description,$nominal,$outstanding_balance,$tax_id,$payment_amount;
    public $tax_amount,$total_amount,$data;
    public $is_readonly = true;
    public function render()
    {
        return view('livewire.operation.expense-edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->recipient = $this->data->recipient;
        $this->reference_type = $this->data->reference_type;
        $this->reference_no = $this->data->reference_no;
        $this->reference_date = $this->data->reference_date;
        $this->nominal = $this->data->nominal;
        $this->outstanding_balance = $this->data->outstanding_balance;
        $this->tax_amount = $this->data->tax_amount;
        $this->tax_id = $this->data->tax_id;
        $this->payment_amount = $this->data->payment_amount;
        $this->total_amount = $this->data->nominal + $this->data->tax_amount;
    }
}
