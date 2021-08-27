<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\CoaGroup;
use App\Models\GeneralLedger;
use App\Models\GeneralLedgerHistory;
use App\Models\Journal;

class CreatePreview extends Component
{
    public $submit_date,$coa_group,$month,$year,$message_error,$general_ledger_number;

    protected $listeners = ['preview-gl'=>'$refresh'];

    public function render()
    {
        return view('livewire.general-ledger.create-preview');
    }

    public function mount(CoaGroup $coa_group)
    {
        $this->coa_group = $coa_group;
        $this->submit_date = date('Y-m-d');
    }

    public function delete(Journal $data)
    {
        $data->status_general_ledger=0;
        $data->save();
        $this->emit('added-journal', "Remove {$data->no_voucher}");
        $this->emit('preview-gl');
    }

    public function submit()
    {
        $this->validate([
            'year' => 'required',
            'month' => 'required'
        ]);
        $this->general_ledger_number = general_ledger_number();
        // check tahun apakah sudah di create dengan bulan dan tahun yang sama
        $check = GeneralLedger::where(['year'=>$this->year,'month'=>$this->month,'coa_group_id'=>$this->coa_group->id])->first();
        if($check){
            $this->message_error = "General Ledger sudah pernah dibuat.";
        }else{
            $data = new GeneralLedger();
            $data->general_ledger_number = $this->general_ledger_number;
            $data->submit_date = $this->submit_date;
            $data->user_id = \Auth::user()->id;
            $data->year = $this->year;
            $data->month = $this->month;
            $data->coa_group_id = $this->coa_group->id;
            $data->save();
            
            foreach(Journal::select('journals.*')->join('coas','coas.id','=','journals.coa_id')->where(['journals.status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->get() as $item){
                $journal = Journal::find($item->id);
                $journal->general_ledger_id = $data->id;
                $journal->status_general_ledger = 2;
                $journal->save();
                // insert history
                $history = new GeneralLedgerHistory();
                $history->general_ledger_id = $data->id;
                $history->journal_id = $journal->id;
                $history->is_revisi = 0;
                $history->save();
            }

            session()->flash('message-success','General Ledger <a href="'.route('general-ledger.detail',$data->id).'">'.$this->general_ledger_number.'</a> Saved .');   
            
            return redirect()->route('general-ledger.create',$this->coa_group->id);
        }
    }
}