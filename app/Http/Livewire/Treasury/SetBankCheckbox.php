<?php

namespace App\Http\Livewire\Treasury;

use Livewire\Component;

class SetBankCheckbox extends Component
{
    public $code_cashflow_id,$value_multiple_bank,$bank_account_id,$transaction_date;
    protected $listeners = ['modalSetBankMultiple'];
    public function render()
    {
        return view('livewire.treasury.set-bank-checkbox');
    }
    public function mount(){}
    public function modalSetBankMultiple($value_multiple_bank)
    {
        $this->value_multiple_bank = $value_multiple_bank;
    }
    public function save()
    {
        $this->validate([
            'bank_account_id'=>'required',
            'transaction_date'=>'required'
        ]);
        foreach($this->value_multiple_bank as $k => $id){
            if($id){
                $data = \App\Models\Journal::find($id);
                $data->bank_account_id = $this->bank_account_id;
                $data->transaction_date = $this->transaction_date;
                $data->save();
            }
        }
        $this->emit('hideModalSetBankMultiple');
        $this->reset(['bank_account_id','transaction_date','value_multiple_bank']);
        session()->flash('message-success',__('Data saved successfully'));
    }
}
