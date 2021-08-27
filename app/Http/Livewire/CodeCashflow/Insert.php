<?php

namespace App\Http\Livewire\CodeCashflow;

use Livewire\Component;

class Insert extends Component
{
    public $group,$code,$name;
    public function render()
    {
        return view('livewire.code-cashflow.insert');
    }
    public function mount()
    {
        \LogActivity::add("Code Cash Flow Insert");
    }
    public function save()
    {
        $this->validate([
            'group'=>'required',
            'name'=>'required',
            'code'=>'required'
        ]);

        $data = new \App\Models\CodeCashflow();
        $data->group = $this->group;
        $data->code = $this->code;
        $data->name = $this->name;
        $data->save();
        
        session()->flash('message-success',__('Data saved successfully'));
        \LogActivity::add("Code Cash Flow Submit {$data->id}");
        return redirect()->to('code-cashflow');
    }
}
