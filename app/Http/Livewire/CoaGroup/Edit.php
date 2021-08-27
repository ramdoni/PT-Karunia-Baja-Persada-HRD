<?php

namespace App\Http\Livewire\CoaGroup;

use Livewire\Component;

class Edit extends Component
{
    public $name,$code,$description,$data;
    public function render()
    {
        return view('livewire.coa-group.edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\CoaGroup::find($id);
        $this->code = $this->data->code;
        $this->name = $this->data->name;
        $this->description = $this->data->description;
        \LogActivity::add("COA Group Edit {$this->data->id}");
    }

    public function save()
    {
        $this->validate([
            'code'=>'required',
            'name'=>'required'
        ]);
        
        $this->data->code = $this->code;
        $this->data->name = $this->name;
        $this->data->description = $this->description;
        $this->data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("COA Group Submit {$this->data->id}");
        return redirect()->to('coa-group');
    }
}
