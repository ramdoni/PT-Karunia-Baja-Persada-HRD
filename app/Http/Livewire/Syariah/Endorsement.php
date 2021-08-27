<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class Endorsement extends Component
{
    public $total_sync=0,$status,$keyword;
    public $perpage=100;
    protected $listeners = ['refresh-page'=>'$refresh'];
    public function render()
    {
        $data = \App\Models\SyariahEndorsement::orderBy('id','DESC')->where('is_temp',0);
        if($this->keyword) $data = $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_endorsement') as $column){
                $table->orWhere($column,'LIKE',"%{$this->keyword}%");
            }
        });
        if($this->status) $data = $data->where('status',$this->status);
        $this->total_sync = \App\Models\SyariahEndorsement::where('status',0)->count();

        return view('livewire.syariah.endorsement')->with(['data'=>$data->paginate($this->perpage)]);
    }
}
