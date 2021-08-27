<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\CoaGroup;
use App\Models\Coa;
use App\Models\Journal;
use App\Models\GeneralLedger;

class Revisi extends Component
{
    public $coa,$year,$month;

    public $data,$total_selected,$coa_group_id,$from_date,$to_date,$create_gl=false,$journal_id=[],$message_error="",$coas,$coa_group;

    public function render()
    {
        return view('livewire.general-ledger.revisi');
    }

    public function mount(GeneralLedger $gl)
    {
        $this->data = $gl;
        $this->coa_group = $this->data->coa_group;
        
        foreach(Journal::select('journals.*')->join('coas','coas.id','=','journals.coa_id')->where(function($table){
            $table->whereNull('journals.general_ledger_id')->orWhere('journals.general_ledger_id',$this->data->id);
        })->where('coas.coa_group_id',$this->coa_group->id)->get() as $journal){
            $this->journal_id[$journal->id] = $journal->status_general_ledger >=1  ? 1 : 0; 
        }
        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')->where(['status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->count();
    }
    
    public function updated()
    {
        if($this->from_date and $this->to_date) $this->coas = Journal::where(['coa_id'=>$this->coa->id])->whereNull('general_ledger_id')->whereBetween('date_journal',[$this->from_date,$this->to_date])->get();
    }

    public function select_all($coa_id)
    {
        $journal = Journal::where(['coa_id'=>$coa_id])->whereNull('general_ledger_id')->get();
        foreach($journal as $item){
            $item->status_general_ledger = 1;
            $item->save();
            $this->journal_id[$item->id] = 1;
        }

        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')->where(['status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->count();
        $this->emit('added-journal', "Select All");
    }

    public function set_status_general_ledger(Journal $selected)
    {
        if($selected->status_general_ledger==1){
            $this->emit('added-journal', "Remove {$selected->no_voucher}");
            $selected->status_general_ledger = 0;
        }elseif($selected->status_general_ledger==2 and $selected->general_ledger_id==$this->data->id){
            $this->emit('added-journal', "Remove {$selected->no_voucher}");
            $selected->status_general_ledger = 0;
            $selected->general_ledger_id = null;
        }else{
            $this->emit('added-journal', "Add {$selected->no_voucher}");
            $selected->status_general_ledger = 1;
        }

        $selected->save();

        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')
                                            ->where(['status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->count();
    }

    public function clear_selected()
    {
        Journal::where(['status_general_ledger'=>1])->update(['status_general_ledger'=>0]);
        
        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')->where(['status_general_ledger'=>1,'coa.coa_group_id'=>$this->coa_group->id])->count();
        $this->emit('added-journal', "Clear selected data...");
    }
}
