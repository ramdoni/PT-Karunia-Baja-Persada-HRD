<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SyariahReinsurance;

class ReinsuranceCheckData extends Component
{
    public $keyword;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'emit-check-data'=>'$refresh',
        'delete-all' => 'deleteAll',
        'keep-all' => 'keepAll',
        'replace-all' => 'replaceAll',
        'delete' => 'delete',
        'replace' => 'replace',
        'keep' => 'keep'
    ];
    public function render()
    {
        $data = $data = SyariahReinsurance::where('is_temp',1);
        if($this->keyword) $data = $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_reinsurance') as $column){
                $table->orWhere($column,'LIKE',"%{$this->keyword}%");
            }
        });
        return view('livewire.syariah.reinsurance-check-data')->with(['data'=>$data->paginate(100)]);
    }
    public function updated()
    {
        if(SyariahReinsurance::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('syariah.reinsurance');
        }
    }
    public function delete(SyariahReinsurance $data){
        $data->delete();
        $this->updated();
    }
    public function replace(SyariahReinsurance $data){
        SyariahReinsurance::where('id',$data->parent_id)->delete();
        $data->update(['is_temp'=>0]);
        $this->updated();
    }
    public function keep(SyariahReinsurance $data){
        $data->update(['is_temp'=>0]);
        $this->updated();
    }
    public function replaceAll()
    {
        foreach(SyariahReinsurance::where('is_temp',1)->get() as $child){
            SyariahReinsurance::find($child->parent_id)->delete();
            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();
        }
        $this->updated();
    }
    public function deleteAll()
    {
        SyariahReinsurance::where('is_temp',1)->delete();
        $this->updated();
    }
    public function keepAll()
    {
        SyariahReinsurance::where('is_temp',1)->update(['is_temp'=>0]);
        $this->updated();
    }
}
