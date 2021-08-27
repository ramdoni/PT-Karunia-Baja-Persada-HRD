<?php

namespace App\Http\Livewire\Treasury\BankAccountCompany;

use Livewire\Component;

class Insert extends Component
{
    public function render()
    {
        return view('livewire.treasury.bank-account-company.insert');
    }
    public $owner,$bank,$no_rekening,$cabang,$open_balance,$code,$coa_id;
   
    public function mount()
    {
        \LogActivity::add("Bank Account Insert");
    }
    public function save()
    {
        $this->validate([
            'code'=>'required',
            'owner'=>'required',
            'bank'=>'required',
            'no_rekening'=>'required',
            'cabang'=>'required',
            'open_balance'=>'required',
            'coa_id'=>'required'
        ]);
        $data = new \App\Models\BankAccount();
        $data->owner = $this->owner;
        $data->bank = $this->bank;
        $data->no_rekening = $this->no_rekening;
        $data->cabang = $this->cabang;
        $data->open_balance = $this->open_balance;
        $data->code = $this->code;
        $data->coa_id = $this->coa_id;
        $data->is_client = 0;
        $data->save();
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("Bank Account Submit {$data->id}");
        return redirect()->to('bank-account-company');
    }
}
