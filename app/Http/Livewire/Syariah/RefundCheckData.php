<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class RefundCheckData extends Component
{
    protected $listeners = [
        'emit-check-data-refund'=>'$refresh',
        'delete-all-refund' => 'deleteAll',
        'keep-all-refund' => 'keepAll',
        'replace-all-refund' => 'replaceAll',
        'delete-refund' => 'delete',
        'replace-refund' => 'replace',
        'keep-refund' => 'keep'
    ];
    public $keyword,$perpage=100;
    public function render()
    {
        $data = \App\Models\SyariahRefund::orderBy('id','DESC')->where('is_temp',1);
        if($this->keyword) $data = $data->where(function($table){
                                                foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_refund') as $column){
                                                    $table->orWhere($column,'LIKE',"%{$this->keyword}%");
                                                }
                                            });
        return view('livewire.syariah.refund-check-data')->with(['data'=>$data->paginate($this->perpage)]);
    }
    public function replaceAll()
    {
        foreach(\App\Models\SyariahRefund::where('is_temp')->get() as $child){
            \App\Models\SyariahRefund::find($child->parent_id)->delete();
            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();
        }
        $this->emit('refresh-page');
    }
    public function deleteAll()
    {
        \App\Models\SyariahRefund::where('is_temp',1)->delete();
        $this->emit('refresh-page');
    }
    public function keepAll()
    {
        \App\Models\SyariahRefund::where('is_temp',1)->update(['is_temp'=>0,'parent_id'=>0]);
    }
    public function delete($id)
    {
        \App\Models\SyariahRefund::find($id)->delete();
    }
    public function keep($id)
    {
        \App\Models\SyariahRefund::find($id)->update(['is_temp'=>0,'parent_id'=>0]);
    }
    public function replace($id)
    {
        $child = \App\Models\SyariahRefund::find($id);
        if($child){
            \App\Models\SyariahRefund::find($child->parent_id)->delete();
            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();   
        }
    }
}
