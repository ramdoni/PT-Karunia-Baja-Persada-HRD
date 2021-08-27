<?php

namespace App\Http\Livewire\IncomeRecoveryClaim;

use Livewire\Component;

class Detail extends Component
{
    public $data,$expense,$list_claim,$titipan_premi;

    public function render()
    {
        return view('livewire.income-recovery-claim.detail');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Income::find($id);
        $this->expense = \App\Models\Expenses::find($this->data->transaction_id);
        $this->list_claim = \App\Models\IncomeRecoveryClaim::where('income_id',$id)->get();
        $this->titipan_premi = \App\Models\IncomeTitipanPremi::where(['income_premium_id'=>$this->data->id,'transaction_type'=>'Recovery Claim'])->get();      
    }
}
