<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class Cancel extends Component
{
    public $total_sync=0,$perpage=100,$keyword,$status;
    protected $listeners = ['refresh-page'=>'$refresh'];
    public function render()
    {
        $data = \App\Models\SyariahCancel::orderBy('id','DESC')->where('is_temp',0);
        if($this->keyword) $data = $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_cancel') as $column){
                $table->orWhere($column,'LIKE',"%{$this->keyword}%");
            }
        });
        if($this->status) $data = $data->where('status',$this->status);
        $this->total_sync = \App\Models\SyariahCancel::where('status',0)->count();
        return view('livewire.syariah.cancel')->with(['data'=>$data->paginate($this->perpage)]);
    }
}
