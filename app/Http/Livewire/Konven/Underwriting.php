<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;

class Underwriting extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$status,$total_sync;
    public function render()
    {
        $data = \App\Models\KonvenUnderwriting::orderBy('id','DESC')->where('is_temp',0);
        if($this->keyword) $data = $data->where('bulan', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('no_reg', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('user_memo', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('user_akseptasi', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('transaksi_id', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('berkas_akseptasi', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tanggal_pengajuan_email', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tanggal_produksi', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('no_reg', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('no_polis', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('no_polis_sistem', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('pemegang_polis', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('alamat', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('cabang', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_peserta_pending', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('up_peserta_pending', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('premi_peserta_pending', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_peserta', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('nomor_peserta_awal', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('nomor_peserta_akhir', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('periode_awal', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('periode_akhir', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('up', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('premi_gross', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('extra_premi', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('discount', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_discount', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_cad_klaim', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('ext_diskon', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('cad_klaim', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('handling_fee', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_fee', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('pph', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_pph', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('ppn', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('jumlah_ppn', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('biaya_polis', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('extsertifikat', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('premi_netto', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('terbilang', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tgl_update_database', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tgl_update_sistem', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('no_berkas_sistem', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tgl_posting_sistem', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('ket_postingan', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tgl_invoice', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('no_kwitansi_debit_note', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('total_gross_kwitansi', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('grace_periode_terbilang', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('grace_periode', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tgl_jatuh_tempo', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('extend_tgl_jatuh_tempo', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('tgl_lunas', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('ket_lampiran', 'LIKE',"%{$this->keyword}%")
                                    ->orWhere('line_bussines', 'LIKE',"%{$this->keyword}%"); 
        
        if($this->status) $data = $data->where('status', $this->status);

        return view('livewire.konven.underwriting')->with(['data'=>$data->paginate(100)]);
    }

    public function mount()
    {
        \LogActivity::add('Konven Underwriting');

        $this->total_sync = \App\Models\KonvenUnderwriting::where('status',1)->where('is_temp',0)->count();
    }
}