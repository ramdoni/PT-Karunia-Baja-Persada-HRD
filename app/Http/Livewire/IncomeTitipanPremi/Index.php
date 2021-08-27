<?php

namespace App\Http\Livewire\IncomeTitipanPremi;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$unit,$status,$payment_date_from,$payment_date_to,$voucher_date,$from_bank_account_id,$to_bank_account_id;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Income::orderBy('id','desc')->where('reference_type','Titipan Premi');
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                        ->orWhere('client','LIKE',"%{$this->keyword}%");

        if($this->unit) $data = $data->where('type',$this->unit);
        if($this->status) $data = $data->where('status',$this->status);
        if($this->payment_date_from and $this->payment_date_to) $data = $data->whereBetween('payment_date',[$this->payment_date_from,$this->payment_date_to]);
        if($this->to_bank_account_id) $data = $data->where('rekening_bank_id',$this->to_bank_account_id);
        
        $total = clone  $data;
 
        return view('livewire.income-titipan-premi.index')->with(['data'=>$data->paginate(100),'total'=>$total->sum('nominal'),'teralokasi'=>$total->sum('payment_amount'),'balance'=>$total->sum('outstanding_balance')]);
    }
     
    public function mount()
    {
        \LogActivity::add("Income Titipan Premi");
    }
}
