<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SyariahUnderwriting;
use App\Models\Income;

class UnderwritingCheckData extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'emit-check-data-underwriting'=>'$refresh',
        'delete-all-underwriting' => 'deleteAll',
        'keep-all-underwriting' => 'keepAll',
        'replace-all-underwriting' => 'replaceAll',
        'delete-underwriting' => 'delete',
        'replace-underwriting' => 'replace',
        'keep-underwriting' => 'keep'
    ];
    public $keyword,$perpage=100;
    public function render()
    {
        $data = SyariahUnderwriting::orderBy('id','DESC')->where('is_temp',1);
        if($this->keyword) $data = $data->where(function($table){
                                                foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_underwritings') as $column){
                                                    $table->orWhere($column,'LIKE',"%{$this->keyword}%");
                                                }
                                            });
        return view('livewire.syariah.underwriting-check-data')->with(['data'=>$data->paginate($this->perpage)]);
    }
    public function updated()
    {
        if(SyariahUnderwriting::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('syariah.underwriting');
        }
    }
    public function replaceAll()
    {
        foreach(SyariahUnderwriting::where('is_temp',1)->get() as $child){
            $income = Income::where(['transaction_table'=>'syariah_underwriting','transaction_id'=>$child->parent_id])->first();
            if($income) $income->delete();
            $parent = SyariahUnderwriting::find($child->parent_id);
            if($parent) $parent->delete();
            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();
        }
        $this->updated();
    }
    public function deleteAll()
    {
        SyariahUnderwriting::where('is_temp',1)->delete();
        $this->updated();
    }
    public function keepAll()
    {
        SyariahUnderwriting::where('is_temp',1)->update(['is_temp'=>0,'parent_id'=>0]);
        $this->updated();
    }
    public function delete($id)
    {
        SyariahUnderwriting::find($id)->delete();
        $this->updated();
    }
    public function keep($id)
    {
        SyariahUnderwriting::find($id)->update(['is_temp'=>0,'parent_id'=>0]);
        $this->updated();
    }
    public function replace($id)
    {
        $child = SyariahUnderwriting::find($id);
        if($child){
            $income = Income::where(['transaction_table'=>'syariah_underwriting','transaction_id'=>$child->parent_id])->first();
            if($income) $income->delete();
            
            $parent = SyariahUnderwriting::find($child->parent_id);
            if($parent) $parent->delete();

            $child->is_temp=0;
            $child->parent_id=0;
            $child->save();   
        }
        $this->updated();
    }
}
