<?php

namespace App\Http\Livewire\CoaGroup;

use Livewire\Component;

class Insert extends Component
{
    public $name,$code,$description;
    public function render()
    {
        return view('livewire.coa-group.insert');
    }
    public function mount()
    {
        \LogActivity::add("COA Group Insert");
    }
    public function save()
    {
        $this->validate([
            'code'=>'required',
            'name'=>'required'
        ]);
        
        $data = new \App\Models\CoaGroup();
        $data->code = $this->code;
        $data->name = $this->name;
        $data->description = $this->description;
        $data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("COA Group Submit {$data->id}");
        return redirect()->to('coa-group');
    }
}
