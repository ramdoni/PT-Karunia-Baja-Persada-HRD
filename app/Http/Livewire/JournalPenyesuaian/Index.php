<?php

namespace App\Http\Livewire\JournalPenyesuaian;

use Livewire\Component;
use App\Models\Journal;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $keyword,$year,$month,$coa_id;

    public function render()
    {
        $data = Journal::orderBy('id','DESC')->groupBy('no_voucher')->where('is_adjusting',1);

        if($this->keyword) $data = $data->where('no_voucher','LIKE',"%{$this->keyword}%");
        if($this->year) $data = $data->whereYear('date_journal',$this->year);
        if($this->month) $data = $data->whereMonth('date_journal',$this->month);
        if($this->coa_id) $data = $data->where('coa_id',$this->coa_id);

        return view('livewire.journal-penyesuaian.index')->with(['data'=>$data->paginate(100)]);
    }
}
