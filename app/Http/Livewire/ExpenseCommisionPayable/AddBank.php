<?php

namespace App\Http\Livewire\ExpenseCommisionPayable;

use Livewire\Component;

class AddBank extends Component
{
    public $bank,$account_number,$owner_name;
    public function render()
    {
        return view('livewire.expense-commision-payable.add-bank');
    }
    public function save()
    {
        $this->validate(
            [
                'bank'=>'required',
                'account_number' => 'required|unique:bank_accounts,no_rekening',
                'owner_name' => 'required'
            ]
        );
        $data = new \App\Models\BankAccount();
        $data->owner = $this->owner_name;
        $data->bank = $this->bank;
        $data->no_rekening = $this->account_number;
        $data->save();
        \LogActivity::add("Add Bank Insert {$data->id}");
        $this->reset();
        $this->emit('emit-add-bank', $data->id);
    }
}
