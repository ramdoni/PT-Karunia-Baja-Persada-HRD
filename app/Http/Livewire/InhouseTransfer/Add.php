<?php

namespace App\Http\Livewire\InhouseTransfer;

use Livewire\Component;

class Add extends Component
{
    public $from_bank_account_id,$to_bank_account_id,$nominal,$date;
    public function render()
    {
        return view('livewire.inhouse-transfer.add');
    }
    public function mount()
    {
        $this->date = \Carbon\Carbon::now()->toDateString();
    }
    public function save()
    {
        $this->validate([
            'from_bank_account_id' => 'required',
            'to_bank_account_id' => 'required',
            'nominal' => 'required',
            'date' => 'required'
        ]);
        $this->nominal = replace_idr($this->nominal);
        $data = new \App\Models\InhouseTransfer();
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->to_bank_account_id = $this->to_bank_account_id;
        $data->nominal = $this->nominal;
        $data->transaction_date = $this->date;
        $data->save();
        // update balance
        $from = \App\Models\BankAccount::find($this->from_bank_account_id);
        if($from){
            $from->open_balance = $from->open_balance - $this->nominal;
            $from->save();

            $balance = new \App\Models\BankAccountBalance();
            $balance->debit = $this->nominal;
            $balance->bank_account_id = $this->from_bank_account_id;
            $balance->status = 1;
            $balance->type = 1; // Inhouse transfer
            $balance->nominal = $from->open_balance;
            $balance->transaction_date = $this->date;
            $balance->save();
        }
        $to = \App\Models\BankAccount::find($this->to_bank_account_id);
        if($to){
            $to->open_balance = $to->open_balance + $this->nominal;
            $to->save();
            $balance = new \App\Models\BankAccountBalance();
            $balance->kredit = $this->nominal;
            $balance->bank_account_id = $this->to_bank_account_id;
            $balance->status = 1;
            $balance->type = 1; // Inhouse transfer
            $balance->nominal = $to->open_balance;
            $balance->transaction_date = $this->date;
            $balance->save();
        }
        \LogActivity::add("Inhouse Transfer Add {$data->id}");

        $this->reset('from_bank_account_id','to_bank_account_id','nominal');
        $this->emit('emit-add-form-hide');
    }
}
