<?php

namespace App\Http\Livewire\ExpenseHandlingFee;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$coa_id,$status;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Expenses::orderBy('id','desc')->where('reference_type','Handling Fee');
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('debit_note','LIKE',"%{$this->keyword}%");
        if($this->coa_id) $data = $data->where('coa_id',$this->coa_id);
        if($this->status) $data = $data->where('status',$this->status);

        $total = clone $data;
        $pph=0;$ppn=0;$total_=0;

        foreach($total->get() as $item){
            $pph += isset($item->uw->jumlah_pph) ? $item->uw->jumlah_pph : 0; 
            $ppn += isset($item->uw->jumlah_ppn) ? $item->uw->jumlah_ppn : 0; 
            $total_ += $item->payment_amount;
        }

        return view('livewire.expense-handling-fee.index')->with(['data'=>$data->paginate(100),
                    'payment_amount'=>$total_,
                    'pph'=>$pph,
                    'ppn'=>$ppn]);
    }

    public function mount()
    {
        \LogActivity::add("Expense Handling Fee");
    }
}