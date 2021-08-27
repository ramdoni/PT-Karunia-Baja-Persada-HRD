<?php

namespace App\Http\Livewire\Finance;

use Livewire\Component;

class Expense extends Component
{
    public $keyword,$unit,$status,$payment_date_from,$payment_date_to;
    public function render()
    {
        $data = \App\Models\Expenses::orderBy('id','DESC')->where('status',2);
        
        if($this->keyword) $data = $data->where(function($table){
            $table->where('description','LIKE', "%{$this->keyword}%")
                    ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                    ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                    ->orWhere('recipient','LIKE',"%{$this->keyword}%");
        });

        if($this->unit) $data = $data->where('type',$this->unit);
        if($this->status) $data = $data->where('status',$this->status);
        if($this->payment_date_from and $this->payment_date_to) $data = $data->whereBetween('payment_date',[$this->payment_date_from,$this->payment_date_to]);
        
        return view('livewire.finance.expense')->with(['data'=>$data->paginate(100)]);
    }
}
