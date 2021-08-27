<?php

namespace App\Http\Livewire\InhouseTransfer;

use Livewire\Component;

class BankAccount extends Component
{
    public $bank_account_id,$total_balance=0;
    protected $listeners = ['emit-add-form-hide'=>'$refresh'];
    public function render()
    {
        $data = \App\Models\BankAccountBalance::orderBy('id','desc');
        if($this->bank_account_id) $data = $data->where('bank_account_id', $this->bank_account_id);
        return view('livewire.inhouse-transfer.bank-account')->with(['data'=>$data->paginate(100)]);
    }
    public function mount()
    {
        $this->total_balance = \App\Models\BankAccount::where('is_client',0)->sum('open_balance');
        \LogActivity::add("Inhouse Transfer - Bank Account");
    }
    public function updated($name)
    {
        if($this->bank_account_id) $this->total_balance = \App\Models\BankAccount::find($this->bank_account_id)->open_balance;
        
    }
}
