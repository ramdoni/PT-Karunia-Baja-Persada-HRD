<?php

namespace App\Http\Livewire\Coa;

use Livewire\Component;

class Insert extends Component
{
    public $coa_group_id,$code,$name,$coa_type_id,$description,$code_voucher,$opening_balance;
    public function render()
    {
        return view('livewire.coa.insert');
    }
    public function mount()
    {
        \LogActivity::add("COA Insert");
    }
    public function save()
    {
        $this->validate([
            'coa_group_id'=>'required',
            'code'=>'required',
            'name'=>'required',
            // 'coa_type_id'=>'required'
        ]);
        
        $data = new \App\Models\Coa();
        $data->coa_group_id = $this->coa_group_id;
        $data->code = $this->code;
        $data->name = $this->name;
        // $data->coa_type_id = $this->coa_type_id;
        $data->description = $this->description;
        $data->code_voucher = $this->code_voucher;
        $data->opening_balance = $this->opening_balance;
        $data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("COA Submit {$data->id}");
        return redirect()->to('coa');
    }
}
