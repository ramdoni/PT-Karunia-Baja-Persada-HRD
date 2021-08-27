<?php

namespace App\Http\Livewire\CodeCashflow;

use Livewire\Component;

class Edit extends Component
{
    public $data,$group,$code,$name;
    public function render()
    {
        return view('livewire.code-cashflow.edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\CodeCashflow::find($id);
        $this->group = $this->data->group;
        $this->code = $this->data->code;
        $this->name = $this->data->name;
        \LogActivity::add("Code Cash Flow Edit {$this->data->id}");
    }

    public function save()
    {
        $this->validate([
            'name'=>'required'
        ]);

        $this->data->name = $this->name;
        $this->data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("Code Cash Flow Submit {$this->data->id}");
        return redirect()->to('code-cashflow');
    }
}
