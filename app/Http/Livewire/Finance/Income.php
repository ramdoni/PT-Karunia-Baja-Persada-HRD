<?php

namespace App\Http\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;

class Income extends Component
{
    use WithPagination;
    public $keyword,$unit,$status,$payment_date_from,$payment_date_to,$voucher_date;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {   
        $data = \App\Models\Income::orderBy('id','desc')->where('reference_type','Premium Receivable')->where('status',2);
        if($this->keyword) $data = $data->where(function($table){
            $table->where('description','LIKE', "%{$this->keyword}%")
            ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
            ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
            ->orWhere('client','LIKE',"%{$this->keyword}%");
        });

        if($this->unit) $data = $data->where('type',$this->unit);
        if($this->payment_date_from and $this->payment_date_to) $data = $data->whereBetween('payment_date',[$this->payment_date_from,$this->payment_date_to]);
        if($this->voucher_date) $data = $data->whereDate('created_at',$this->voucher_date);

        return view('livewire.finance.income')->with(['data'=>$data->paginate(100)]);
    }
}