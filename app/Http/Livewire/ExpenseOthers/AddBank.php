<?php

namespace App\Http\Livewire\ExpenseOthers;

use Livewire\Component;

class AddBank extends Component
{
    public $bank,$account_number,$owner_name;
    public function render()
    {
        return view('livewire.expense-others.add-bank');
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
        $data->is_client = 2;
        $data->save();
        $this->reset();
        $this->emit('emit-add-bank', $data->id);
    }
}
