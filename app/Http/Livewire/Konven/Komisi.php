<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;

class Komisi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$total_sync=0,$status;
    public function render()
    {
        $data = \App\Models\KonvenKomisi::orderBy('id','DESC')->where('is_temp',0);
        if($this->keyword) $data = $data->where(function($table){
                                                    $table->where('user', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('tgl_memo', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('no_reg', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('no_polis', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('no_polis_sistem', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('pemegang_polis', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('produk', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('tgl_invoice', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('no_kwitansi', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('total_peserta', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('no_peserta', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('total_up', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('total_premi_gross', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('em', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('disc_pot_langsung', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('premi_netto_yg_dibayarkan', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('perkalian_biaya_penutupan', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('fee_base', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_fee_base', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('maintenance', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_maintenance', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('admin_agency', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_admin_agency', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('agen_penutup', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_agen_penutup', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('operasional_agency', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_operasional_agency', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('handling_fee_broker', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_handling_fee_broker', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('referral_fee', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('biaya_rf', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('pph', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('jumlah_pph', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('ppn', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('jumlah_ppn', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('cadangan_klaim', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('jml_cadangan_klaim', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('klaim_kematian', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('pembatalan', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('total_komisi', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('tujuan_pembayaran', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('no_rekening', 'LIKE',"{$this->keyword}")
                                                        ->orWhere('tgl_lunas', 'LIKE',"{$this->keyword}");
                                                });
        if($this->status) $data = $data->where('status',$this->status);
        return view('livewire.konven.komisi')->with(['data'=>$data->paginate(100)]);
    }
    public function mount()
    {
        $this->total_sync = \App\Models\KonvenKomisi::where('status',0)->count();
    }
}
