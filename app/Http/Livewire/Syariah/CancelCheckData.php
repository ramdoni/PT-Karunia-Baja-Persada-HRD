<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class CancelCheckData extends Component
{
    protected $listeners = [
        'emit-check-data-cancel'=>'$refresh',
        'delete-all-cancel' => 'deleteAll',
        'keep-all-cancel' => 'keepAll',
        'replace-all-cancel' => 'replaceAll',
        'delete-cancel' => 'delete',
        'replace-cancel' => 'replace',
        'keep-cancel' => 'keep'
    ];
    public $keyword,$perpage=100;
    public function render()
    {
        $data = \App\Models\SyariahCancel::orderBy('id','DESC')->where('is_temp',1);
        if($this->keyword) $data = $data->where(function($table){
                                                foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_cancel') as $column){
                                                    $table->orWhere($column,'LIKE',"%{$this->keyword}%");
                                                }
                                            });
        return view('livewire.syariah.cancel-check-data')->with(['data'=>$data->paginate($this->perpage)]);
    }
    public function updated()
    {
        if(\App\Models\SyariahCancel::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('All data was processed successfully'));
            return redirect()->route('syariah.underwriting');
        }
    }
    public function replaceAll()
    {
        foreach(\App\Models\SyariahCancel::where('is_temp')->get() as $child){
            \App\Models\SyariahCancel::find($child->parent_id)->delete();
            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();
        }
        $this->updated();
    }
    public function deleteAll()
    {
        \App\Models\SyariahCancel::where('is_temp',1)->delete();
        $this->updated();
    }
    public function keepAll()
    {
        \App\Models\SyariahCancel::where('is_temp',1)->update(['is_temp'=>0,'parent_id'=>0]);
        $this->updated();
    }
    public function delete($id)
    {
        \App\Models\SyariahCancel::find($id)->delete();
        $this->updated();
    }
    public function keep($id)
    {
        \App\Models\SyariahCancel::find($id)->update(['is_temp'=>0,'parent_id'=>0]);
        $this->updated();
    }
    public function replace($id)
    {
        $child = \App\Models\SyariahCancel::find($id);
        if($child){
            \App\Models\SyariahCancel::find($child->parent_id)->delete();
            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();   
        }
        $this->updated();
    }
}
