<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\Coa;
use App\Models\CoaGroup;
use App\Models\Journal;

class Syariah extends Component
{
    public $coa_group_id,$from_date,$to_date;

    public function render()
    {
        $coas = Coa::orderBy('name','ASC');
        
        if($this->coa_group_id) $coas->where('coa_group_id', $this->coa_group_id);
        
        if($this->from_date and $this->to_date) $coas->whereBetween('date_journal',[$this->from_date,$this->to_date]);

        return view('livewire.general-ledger.syariah')->with(['coas'=>$coas->get()]);
    }
}