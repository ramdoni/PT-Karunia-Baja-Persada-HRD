<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SyariahUnderwriting;

class Underwriting extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $total_sync=0,$keyword,$status;
    protected $listeners = ['refresh-page'=>'$refresh'];
    public function render()
    {
        $data = SyariahUnderwriting::orderBy('id','DESC')->where('is_temp',0);
        if($this->keyword) $data = $data->where(function($table){
                        foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_underwritings') as $column){
                            $table->orWhere($column,'LIKE',"%{$this->keyword}%");
                        }
                    });
        if($this->status) $data = $data->where('status',$this->status);
        $this->total_sync = SyariahUnderwriting::where(['is_temp'=>0,'status'=>1])->count();
        return view('livewire.syariah.underwriting')->with(['data'=>$data->paginate(100)]);
    }

    public function delete(SyariahUnderwriting $data)
    {
        $data->delete();
    }
}
