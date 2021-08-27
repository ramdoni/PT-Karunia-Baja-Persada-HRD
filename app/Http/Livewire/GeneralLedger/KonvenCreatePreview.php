<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\Coa;
use App\Models\GeneralLedger;
use App\Models\Journal;

class KonvenCreatePreview extends Component
{
    public $coa_group_id,$submit_date;

    protected $listeners = ['preview-gl'=>'$refresh'];

    public function render()
    {
        $coas = Coa::orderBy('name','ASC');
        
        if($this->coa_group_id) $coas->where('coa_group_id', $this->coa_group_id);

        return view('livewire.general-ledger.konven-create-preview')->with(['coas'=>$coas->get()]);
    }

    public function mount()
    {
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
        $data = new GeneralLedger();
        $data->submit_date = $this->submit_date;
        $data->user_id = \Auth::user()->id;
        $data->save();

        foreach(Journal::where('status_general_ledger',1)->get() as $journal){
            $journal->general_ledger_id = $data->id;
            $journal->status_general_ledger = 2;
            $journal->save();
        }

        session()->flash('message-success','General Ledger submit.');   
        
        return redirect()->route('general-ledger.konven');
    }
}