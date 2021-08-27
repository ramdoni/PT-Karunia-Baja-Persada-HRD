<?php

namespace App\Http\Livewire\CoaType;

use Livewire\Component;

class Insert extends Component
{
    public $name,$description;
    public function render()
    {
        return view('livewire.coa-type.insert');
    }

    public function save()
    {
        $this->validate([
            'name'=>'required'
        ]);
        
        $data = new \App\Models\CoaType();
        $data->name = $this->name;
        $data->description = $this->description;
        $data->save();
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('coa-type');
    }
}
