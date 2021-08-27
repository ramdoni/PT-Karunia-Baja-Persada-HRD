<?php

namespace App\Http\Livewire\DataTeknis;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['listenHideModal'=>'viewHideModal','listenUploaded'=>'viewUploaded'];
    public $keyword;
    public function render()
    {
        $data = \App\Models\Teknis::orderBy('id','DESC');
        if($this->keyword) 
            $data = $data->where('no_debit_note','LIKE',"%{$this->keyword}%")
                            ->orWhere('no_polis','LIKE',"%{$this->keyword}%")
                            ->orWhere('pemegang_polis','LIKE',"%{$this->keyword}%")
                            ->orWhere('bulan','LIKE',"%{$this->keyword}%")
                            ;

        return view('livewire.data-teknis.index')->with(['data'=>$data->paginate(100)]);
    }
    
    public function viewUploaded()
    {
        $this->emit('uploaded');
    }

    public function viewHideModal()
    {
        $this->emit('hideModal');
    }

    public function clear_db()
    {
        \App\Models\Teknis::truncate();
    }
}
