<?php

namespace App\Http\Livewire\AccountingJournal;

use Livewire\Component;
use App\Models\Journal;

class Adjusting extends Component
{
    public $data,$coas=[],$count_expand=1,$nominal=0;

    public function render()
    {
        return view('livewire.accounting-journal.adjusting');
    }

    public function mount(Journal $data,$coas)
    {
        $this->data = $data;
        $this->coas = $coas;
        $this->nominal = Journal::where('no_voucher',$this->data->no_voucher)->sum('debit');
    }
}
