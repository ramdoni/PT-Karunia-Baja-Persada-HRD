<?php

namespace App\Http\Livewire\IncomeRecoveryClaim;

use Livewire\Component;

class AddBank extends Component
{
    public $bank,$account_number,$owner_name;
    public function render()
    {
        return view('livewire.income-recovery-claim.add-bank');
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
        $this->reset();
        $this->emit('emit-add-bank', $data->id);
    }
}
