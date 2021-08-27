<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\Coa;
use App\Models\Journal;

class KonvenCreate extends Component
{
    public $coa_group_id,$from_date,$to_date,$create_gl=false,$journal_id=[];

    public function render()
    {
        $coas = Coa::orderBy('name','ASC');
        
        if($this->coa_group_id) $coas->where('coa_group_id', $this->coa_group_id);
        
        if($this->from_date and $this->to_date) $coas->whereBetween('date_journal',[$this->from_date,$this->to_date]);
        
        foreach(Journal::whereNull('general_ledger_id')->get() as $journal){
            $this->journal_id[$journal->id] = $journal->status_general_ledger ==1 ? 1 : 0; 
        }

        return view('livewire.general-ledger.konven-create')->with(['coas'=>$coas->get(),'total_selected'=>Journal::where(['status_general_ledger'=>1])->count()]);
    }

    public function mount()
    {
        foreach(Journal::whereNull('general_ledger_id')->get() as $journal){
            $this->journal_id[$journal->id] = $journal->status_general_ledger ==1 ? 1 : 0; 
        }
    }
    
    public function select_all($coa_id)
    {
        $journal = Journal::where(['coa_id'=>$coa_id])->whereNull('general_ledger_id')->get();
        foreach($journal as $item){
            $item->status_general_ledger = 1;
            $item->save();
            $this->journal_id[$item->id] = 1;
        }

        $this->emit('added-journal', "Select All");
    }

    public function set_status_general_ledger(Journal $selected)
    {
        if($selected->status_general_ledger==0){
            $this->emit('added-journal', "Add {$selected->no_voucher}");
            $selected->status_general_ledger = 1;
        }
        else{
            $this->emit('added-journal', "Remove {$selected->no_voucher}");
            $selected->status_general_ledger = 0;
        }

        $selected->save();
    }

    public function clear_selected()
    {
        Journal::where(['status_general_ledger'=>1])->update(['status_general_ledger'=>0]);
        
        $this->emit('added-journal', "Clear selected data..");
    }
}
