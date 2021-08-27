<?php

namespace App\Http\Livewire\CodeCashflow;

use Livewire\Component;

class Index extends Component
{
    public $keyword,$group;
    public function render()
    {
        $data = \App\Models\CodeCashflow::orderBy('id','DESC');
        if($this->keyword) $data = $data->where('code','LIKE',"%{$this->keyword}%")->orWhere('name','LIKE',"%{$this->keyword}%");
        if($this->group) $data = $data->where('group',$this->group);
        
        return view('livewire.code-cashflow.index')->with(['data'=>$data->get()]);
    }
    public function mount()
    {
        \LogActivity::add("Code Cash Flow");
    }
}