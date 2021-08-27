<?php

namespace App\Http\Livewire\Coa;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$coa_group_id,$is_others_expense,$is_others_income;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Coa::orderBy('id','DESC');
        if($this->keyword) $data = $data->where('code','LIKE', '%'.$this->keyword.'%')
                                                    ->orWhere('name','LIKE', '%'.$this->keyword.'%')
                                                    ->orWhere('description','LIKE', '%'.$this->keyword.'%');
        if($this->coa_group_id) $data = $data->where('coa_group_id',$this->coa_group_id);

        return view('livewire.coa.index')->with(['data'=>$data->paginate(50)]);
    }
    public function mount()
    {
        \LogActivity::add("COA");

        foreach(\App\Models\Coa::all() as $k => $coa){
            $this->is_others_expense[$coa->id] = $coa->is_others_expense;
            $this->is_others_income[$coa->id] = $coa->is_others_income;
        }
    }
    public function update_($type,$id)
    {
        $coa = \App\Models\Coa::find($id);
        
        if($type=='income') \App\Models\Coa::find($id)->update(['is_others_income'=>($coa->is_others_income==1? 0: 1)]);
        if($type=='expense') \App\Models\Coa::find($id)->update(['is_others_expense'=>($coa->is_others_expense==1? 0: 1)]);
    }
}