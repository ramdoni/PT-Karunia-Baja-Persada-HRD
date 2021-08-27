<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;
use Livewire\WithPagination;

class AddTitipanPremi extends Component
{
    use WithPagination;
    public $keyword,$unit,$status,$payment_date,$voucher_date,$from_bank_account_id;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Income::orderBy('id','desc')->where(['reference_type' => 'Titipan Premi','status'=>1]);
        if($this->keyword) $data = $data->where(function($table){
                                            $table->where('description','LIKE', "%{$this->keyword}%")
                                            ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                            ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                            ->orWhere('client','LIKE',"%{$this->keyword}%");
                                            });

        if($this->unit) $data = $data->where('type',$this->unit);
        if($this->status) $data = $data->where('status',$this->status);
        if($this->payment_date) $data = $data->where('payment_date',$this->payment_date);
        if($this->voucher_date) $data = $data->whereDate('created_at',$this->voucher_date);
        if($this->from_bank_account_id) $data = $data->where('from_bank_account_id',$this->from_bank_account_id);

        return view('livewire.income-premium-receivable.add-titipan-premi')->with(['data'=>$data->paginate(100)]);
    }
}
