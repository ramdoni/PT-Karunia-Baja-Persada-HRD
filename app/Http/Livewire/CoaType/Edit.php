<?php

namespace App\Http\Livewire\CoaType;

use Livewire\Component;

class Edit extends Component
{
    public $name,$description,$data;

    public function render()
    {
        return view('livewire.coa-type.edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\CoaType::find($id);
        $this->name = $this->data->name;
        $this->description = $this->data->description;
    }

    public function save()
    {
        $this->validate([
            'name'=>'required'
        ]);
        
        $this->data->name = $this->name;
        $this->data->description = $this->description;
        $this->data->save();
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('coa-type');
    }
}
