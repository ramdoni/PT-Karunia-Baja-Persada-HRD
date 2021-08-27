<?php

namespace App\Http\Livewire\ExpenseClaim;

use Livewire\Component;
use App\Models\BankAccount;

class AddBank extends Component
{
    public $bank,$account_number,$owner_name,$to_bank_account_id;

    public function render()
    {
        return view('livewire.expense-claim.add-bank');
    }

    public function updated($propertyName)
    {
        if($propertyName=='to_bank_account_id'){
            $this->emit('emit-add-bank',BankAccount::find($this->$propertyName));
        }
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
        
        $data = new BankAccount();
        $data->owner = $this->owner_name;
        $data->bank = $this->bank;
        $data->no_rekening = $this->account_number;
        $data->save();

        $this->to_bank_account_id = $data->id;

        $this->reset();
        $this->emit('emit-add-bank', $data->id);
    }
}
