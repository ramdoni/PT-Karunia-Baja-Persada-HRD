<?php

namespace App\Http\Livewire\IncomeRecoveryRefund;

use Livewire\Component;
use App\Models\Income;
use App\Models\Policy;
use App\Models\IncomePeserta;
use App\Models\IncomeTitipanPremi;

class Detail extends Component
{
    public $data,$income,$add_pesertas,$titipan_premi;
    public function render()
    {
        return view('livewire.income-recovery-refund.detail');
    }
    public function mount($id)
    {
        $this->income = Income::find($id);
        $this->data = Policy::find($this->income->policy_id);
        $this->add_pesertas = IncomePeserta::where('income_id',$this->income->id)->get(); 
        $this->titipan_premi = IncomeTitipanPremi::where(['income_premium_id'=>$this->income->id,'transaction_type'=>'Recovery Refund'])->get();      
    }
}