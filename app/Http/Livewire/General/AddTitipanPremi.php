<?php

namespace App\Http\Livewire\General;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Income;

class AddTitipanPremi extends Component
{
    use WithPagination;
    
    public $keyword,$unit,$status,$payment_date,$voucher_date,$to_bank_account_id,$from_bank_account_id;
    
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = Income::orderBy('id','desc')->where(['reference_type' => 'Titipan Premi','status'=>1]);
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
        if($this->to_bank_account_id) $data = $data->where('rekening_bank_id',$this->to_bank_account_id);
        if($this->from_bank_account_id) $data = $data->where('from_bank_account_id',$this->from_bank_account_id);

        return view('livewire.general.add-titipan-premi')->with(['data'=>$data->paginate(100)]);
    }
}
