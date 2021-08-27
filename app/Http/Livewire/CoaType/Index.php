<?php

namespace App\Http\Livewire\CoaType;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $keyword;
    use WithPagination;

    public function render()
    {
        $data = \App\Models\CoaType::orderBy('id','DESC');
        if($this->keyword) $data = $data->where('name','LIKE', '%'.$this->keyword.'%')
                                                    ->orWhere('description','LIKE', '%'.$this->keyword.'%');
        return view('livewire.coa-type.index')->with(['data'=>$data->paginate(50)]);
    }
    
    public function delete($id)
    {
        \App\Models\CoaType::find($id)->delete();
    }
}
