<?php

namespace App\Http\Livewire\InhouseTransfer;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['emit-add-form-hide'=>'$refresh'];
    public function render()
    {
        $data = \App\Models\InhouseTransfer::orderBy('id','DESC');
        return view('livewire.inhouse-transfer.index')->with(['data'=>$data->paginate(100)]);
    }
    public function mount()
    {
        \LogActivity::add("Inhouse Transfer");
    }
}
