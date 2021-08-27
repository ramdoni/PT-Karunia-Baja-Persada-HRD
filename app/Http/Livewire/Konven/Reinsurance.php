<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;
class Reinsurance extends Component
{
    public $keyword,$total_sync;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\KonvenReinsurance::orderBy('id','DESC');
        if($this->keyword) $data = $data->where('no_polis','LIKE',"%{$this->keyword}%")
                                        ->orWhere('pemegang_polis', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('peserta', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('uang_pertanggungan', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('uang_pertanggungan_reas', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('premi_gross_ajri', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('premi_reas', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('komisi_reinsurance', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('premi_reas_netto', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('keterangan', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('kirim_reas', 'LIKE',"%{$this->keyword}")
                                        ->orWhere('broker_re', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('reasuradur', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('bulan', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('ekawarsa_jangkawarsa', 'LIKE',"%{$this->keyword}%")
                                        ->orWhere('produk', 'LIKE',"%{$this->keyword}%");
        return view('livewire.konven.reinsurance')->with(['data'=>$data->paginate(100)]);
    }
    public function mount()
    {
        $this->total_sync = \App\Models\KonvenReinsurance::where('status',1)->count();
    }
}
