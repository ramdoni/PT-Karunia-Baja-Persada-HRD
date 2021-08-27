<?php

namespace App\Http\Livewire\CoaGroup;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $keyword;
    use WithPagination;

    public function render()
    {
        $data = \App\Models\CoaGroup::orderBy('id','DESC');
        if($this->keyword) $data = $data->where('code','LIKE', '%'.$this->keyword.'%')
                                                    ->orWhere('name','LIKE', '%'.$this->keyword.'%')
                                                    ->orWhere('description','LIKE', '%'.$this->keyword.'%');

        return view('livewire.coa-group.index')->with(['data'=>$data->paginate(50)]);
    }
    public function mount()
    {
        \LogActivity::add("COA Group");
    }
    public function delete($id)
    {
        \App\Models\CoaGroup::find($id)->delete();
    }
}