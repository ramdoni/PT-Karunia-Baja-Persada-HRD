<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use \App\Models\SyariahReinsurance;
use Livewire\WithPagination;

class Reinsurance extends Component
{
    public $total_sync,$keyword,$status;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = SyariahReinsurance::orderBy('id','DESC')->where('is_temp',0);
        if($this->keyword) $data = $data->where(function($table){
                        foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_reinsurance') as $column){
                            $table->orWhere($column,'LIKE',"%{$this->keyword}%");
                        }
                    });
        if($this->status) $data = $data->where('status',$this->status);
        $this->total_sync = SyariahReinsurance::where(['is_temp'=>0,'status'=>1])->count();

        return view('livewire.syariah.reinsurance')->with(['data'=>$data->paginate(100)]);
    }
    public function delete(SyariahReinsurance $item)
    {
        $item->delete();
    }
}
