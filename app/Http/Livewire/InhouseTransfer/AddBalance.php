<?php

namespace App\Http\Livewire\InhouseTransfer;

use Livewire\Component;

class AddBalance extends Component
{
    public $bank_account_id,$nominal,$date;
    public function render()
    {
        return view('livewire.inhouse-transfer.add-balance');
    }
    public function mount()
    {
        $this->date = \Carbon\Carbon::now()->toDateString();
    }
    public function save()
    {
        $this->validate(
            [
                'bank_account_id' => 'required',
                'nominal' => 'required',
                'date' => 'required'
            ]);
        $this->nominal = replace_idr($this->nominal);
        $bank = \App\Models\BankAccount::find($this->bank_account_id);

        $balance = new \App\Models\BankAccountBalance();
        $balance->debit = $this->nominal;
        $balance->bank_account_id = $this->bank_account_id;
        $balance->status = 1;
        $balance->type = 2; // Open balance
        $balance->nominal = $bank->open_balance;
        $balance->transaction_date = $this->date;
        $balance->save();
        if($bank){
            $bank->open_balance = $bank->open_balance + $this->nominal;
            $bank->save();
        }
        \LogActivity::add("Inhouse Transfer Add Balance {$balance->id}");

        $this->emit('emit-add-balance-hide');
    }
}
