<?php

namespace App\Http\Livewire\ExpenseEndorsement;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$coa_id,$status;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Expenses::orderBy('id','desc')->where('reference_type','Endorsement');
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        //->orWhere('debit_note','LIKE',"%{$this->keyword}%")
                                        ;
        if($this->coa_id) $data = $data->where('coa_id',$this->coa_id);
        if($this->status) $data = $data->where('status',$this->status);

        return view('livewire.expense-endorsement.index')->with(['data'=>$data->paginate(100)]);
    }
    public function mount()
    {
        \LogActivity::add("Expense Endorsement");
    }
}