<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;
class Claim extends Component
{
    public $keyword,$total_sync;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\KonvenClaim::orderBy('id','DESC');
        if($this->keyword)
            $data = $data->where('nomor_polis',"LIKE","{$this->keyword}")
                            ->orWhere('nama_pemegang',"LIKE","{$this->keyword}")
                            ->orWhere('nomor_partisipan',"LIKE","{$this->keyword}")
                            ->orWhere('nama_partisipan',"LIKE","{$this->keyword}")
                            ->orWhere('status',"LIKE","{$this->keyword}");
        return view('livewire.konven.claim')->with(['data'=>$data->paginate(100)]);
    }
    public function mount()
    {
        $this->total_sync = \App\Models\KonvenClaim::where('status_claim',1)->count();
    }
}