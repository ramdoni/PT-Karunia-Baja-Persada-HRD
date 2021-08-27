<?php

namespace App\Http\Livewire\IncomeTitipanPremi;

use Livewire\Component;

class Detail extends Component
{
    public $no_voucher,$reference_no,$type,$payment_amount,$payment_date,$reference_type,$description,$data,$nominal,$from_bank_account_id,$to_bank_account_id,$is_readonly=false;
    
    public $titipan_premi,$temp_titipan_premi=[],$temp_arr_titipan_id=[],$total_titipan_premi=0;

    public function render()
    {
        return view('livewire.income-titipan-premi.detail');
    }

    public function mount($id)
    { 
        $this->data = \App\Models\Income::find($id);
        $this->no_voucher = generate_no_voucher_income();
        \LogActivity::add("Income Titipan Premi Detail {$id}");
    }
}
