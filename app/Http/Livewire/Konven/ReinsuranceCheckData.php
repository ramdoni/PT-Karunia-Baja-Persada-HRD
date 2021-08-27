<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;

class ReinsuranceCheckData extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$total_sync=0;
    protected $listeners = [
                            'emit-check-data'=>'$refresh',
                            'delete-all'=>'deleteAll',
                            'keep-all'=>'keepAll',
                            'replace-all'=>'replaceAll',
                            'replace'=>'replace',
                            'delete'=>'delete',
                            'keep'=>'keep',
                            'replace-all'=>'replaceAll'
                        ];
    public function render()
    {
        $data = \App\Models\KonvenReinsurance::orderBy('id','DESC')->where('is_temp',1);
        if($this->keyword) $data = $data->where(function($table){
                                        $table->where('no_polis','LIKE',"%{$this->keyword}%")
                                        ->orWhere('pemegang_polis', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('peserta', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('uang_pertanggungan', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('uang_pertanggungan_reas', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('premi_gross_ajri', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('premi_reas', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('komisi_reinsurance', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('premi_reas_netto', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('keterangan', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('kirim_reas', 'LIKE',"%{$this->keyword}")
                                        ->orWhere('broker_re', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('reasuradur', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('bulan', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('ekawarsa_jangkawarsa', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('produk', 'LIKE',"%{$this->keyword}%");
                                        });
        return view('livewire.konven.reinsurance-check-data')->with(['data'=>$data->paginate(100)]);
    }
    public function keep($id)
    {
        \App\Models\KonvenReinsurance::where('id',$id)->update(['is_temp'=>0]);
        if(\App\Models\KonvenReinsurance::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.reinsurance');
        }
    }
    public function deleteAll()
    {
        \App\Models\KonvenReinsurance::where('is_temp',1)->delete();
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.reinsurance');
    }
    public function keepAll()
    {
        \App\Models\KonvenReinsurance::where('is_temp',1)->update(['is_temp'=>0]);
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.reinsurance');
    }
    public function replaceAll()
    {
        $data = \App\Models\KonvenReinsurance::where('is_temp',1)->get();
        foreach($data as $child){
            // delete parent
            \App\Models\KonvenReinsurance::find($child->parent_id)->delete();
            // Check Expense
            $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_komisi','transaction_id'=>$child->parent_id])->first();
            if($expense and $expense->status==1) $expense->delete(); // delete expense
            $child->is_temp = 0;
            $child->parent_id = 0;
            $child->save();
        }
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function delete($id)
    {   
        \App\Models\KonvenReinsurance::find($id)->delete();
        if(\App\Models\KonvenReinsurance::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.reinsurance');
        }
    }
    public function replace($id)
    {
        $child = \App\Models\KonvenReinsurance::where('id',$id)->first();
        $skip = false;
        // Check Expense
        $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_reinsurance','transaction_id'=>$child->parent_id])->first();
        if($expense){
            if($expense->status==2)
                $skip=true;
            else $expense->delete(); // delete expense
        }
        // Check Income
        $income = \App\Models\Income::where(['transaction_table'=>'konven_reinsurance','transaction_id'=>$child->parent_id])->first();
        if($income){
            if($income->status==2)
                $skip=true;
            else $income->delete();
        }
        // delete parent
        if(!$skip) \App\Models\KonvenReinsurance::find($child->parent_id)->delete();
        \App\Models\KonvenReinsurance::where('id',$id)->update(['is_temp'=>0,'parent_id'=>0]);
        if(\App\Models\KonvenReinsurance::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
}