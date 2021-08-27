<?php

namespace App\Http\Livewire\Coa;

use Livewire\Component;

class Edit extends Component
{
    public $coa_group_id,$code,$name,$coa_type_id,$description,$data,$code_voucher,$opening_balance;

    public function render()
    {
        return view('livewire.coa.edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Coa::find($id);
        $this->coa_group_id = $this->data->coa_group_id;
        $this->code = $this->data->code;
        $this->name = $this->data->name;
        $this->coa_type_id = $this->data->coa_type_id;
        $this->description = $this->data->description;
        $this->code_voucher = $this->data->code_voucher;
        $this->opening_balance = $this->data->opening_balance;

        \LogActivity::add("COA Edit {$id}");
    }

    public function save()
    {
        $this->validate([
            //'coa_group_id'=>'required',
            //'code'=>'required',
            'name'=>'required',
            // 'coa_type_id'=>'required',
            'code_voucher'=>'required'
        ]);
        
        //$this->data->coa_group_id = $this->coa_group_id;
        //$this->data->code = $this->code;
        $this->data->name = $this->name;
        $this->data->coa_type_id = $this->coa_type_id;
        $this->data->description = $this->description;
        $this->data->code_voucher = $this->code_voucher;
        $this->data->opening_balance = $this->opening_balance;
        $this->data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("COA Submit {$this->data->id}");
        return redirect()->to('coa');
    }
}
