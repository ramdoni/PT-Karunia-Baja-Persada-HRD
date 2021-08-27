<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;

class MemoPosCheckData extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$total_sync=0;
    protected $listeners = ['emit-check-data-memo-pos'=>'$refresh','replace-memo-pos'=>'replaceNew','delete-memo-pos'=>'deleteNew','replace-all-memo-pos'=>'replaceAll','delete-old-memo-pos'=>'deleteOld','keep-memo-pos'=>'keepNew'];
    public function render()
    {
        $data = \App\Models\KonvenMemo::orderBy('updated_at','DESC')->where('is_temp',1);
        if($this->keyword) $data = $data->where('bulan','LIKE', "%{$this->keyword}%")
                                        ->orWhere('user','LIKE', "%{$this->keyword}%")
                                        ->orWhere('user_akseptasi','LIKE', "%{$this->keyword}%")
                                        ->orWhere('berkas_akseptasi','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_pengajuan_email','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_produksi','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_reg','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_reg_sistem','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_dn_cn','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_dn_cn_sistem','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jenis_po','LIKE', "%{$this->keyword}%")
                                        ->orWhere('status','LIKE', "%{$this->keyword}%")
                                        ->orWhere('posting','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jenis_po_2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ket_perubahan1','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ket_perubahan2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_polis','LIKE', "%{$this->keyword}%")
                                        ->orWhere('pemegang_polis','LIKE', "%{$this->keyword}%")
                                        ->orWhere('cabang','LIKE', "%{$this->keyword}%")
                                        ->orWhere('produk','LIKE', "%{$this->keyword}%")
                                        ->orWhere('alamat','LIKE', "%{$this->keyword}%")
                                        ->orWhere('up_tujuan_surat','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tujuan_pembayaran','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_rekening','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jumlah_peserta_pending','LIKE', "%{$this->keyword}%")
                                        ->orWhere('up_peserta_pending','LIKE', "%{$this->keyword}%")
                                        ->orWhere('premi_peserta_pending','LIKE', "%{$this->keyword}%")
                                        ->orWhere('peserta','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_peserta_awal','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_peserta_akhir','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_sertifikat_awal','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_sertifikat_akhir','LIKE', "%{$this->keyword}%")
                                        ->orWhere('periode_awal','LIKE', "%{$this->keyword}%")
                                        ->orWhere('periode_akhir','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_proses','LIKE', "%{$this->keyword}%")
                                        ->orWhere('movement','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_invoice','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_invoice2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_kwitansi_finance','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_kwitansi_finance2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('total_gross_kwitansi','LIKE', "%{$this->keyword}%")
                                        ->orWhere('total_gross_kwitansi2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jumlah_peserta_update','LIKE', "%{$this->keyword}%")
                                        ->orWhere('up_cancel','LIKE', "%{$this->keyword}%")
                                        ->orWhere('premi_gross_cancel','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extra_premi','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extextra','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpextra','LIKE', "%{$this->keyword}%")
                                        ->orWhere('diskon_premi','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_diskon','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rp_diskon','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extdiskon','LIKE', "%{$this->keyword}%")
                                        ->orWhere('fee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_handling_fee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ext_fee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rp_fee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tampilan_fee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('pph','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_pph','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extpph','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rppph','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ppn','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_ppn','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extppn','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpppn','LIKE', "%{$this->keyword}%")
                                        ->orWhere('biaya_sertifikat','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extbiayasertifikat','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpbiayasertifikat','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extpstsertifikat','LIKE', "%{$this->keyword}%")
                                        ->orWhere('net_sblm_endors','LIKE', "%{$this->keyword}%")
                                        ->orWhere('data_stlh_endors','LIKE', "%{$this->keyword}%")
                                        ->orWhere('up_stlh_endors','LIKE', "%{$this->keyword}%")
                                        ->orWhere('premi_gross_endors','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extra_premi2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extem','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpxtra','LIKE', "%{$this->keyword}%")
                                        ->orWhere('discount','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_discount','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ext_discount','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpdiscount','LIKE', "%{$this->keyword}%")
                                        ->orWhere('handling_fee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extfee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpfee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tampilanfee','LIKE', "%{$this->keyword}%")
                                        ->orWhere('pph2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_pph2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extpph2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rppph2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ppn2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('jml_ppn2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extppn2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpppn2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('biaya_sertifikat2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extbiayasertifikat2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('rpbiayasertifikat2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('extpstsertifikat2','LIKE', "%{$this->keyword}%")
                                        ->orWhere('net_stlh_endors','LIKE', "%{$this->keyword}%")
                                        ->orWhere('refund','LIKE', "%{$this->keyword}%")
                                        ->orWhere('terbilang','LIKE', "%{$this->keyword}%")
                                        ->orWhere('ket_lampiran','LIKE', "%{$this->keyword}%")
                                        ->orWhere('grace_periode','LIKE', "%{$this->keyword}%")
                                        ->orWhere('grace_periode_nominal','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_jatuh_tempo','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_update_database','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_update_sistem','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_berkas_sistem','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_posting_sistem','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_debit_note_finance','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_bayar','LIKE', "%{$this->keyword}%")
                                        ->orWhere('tgl_output_email','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_berkas2','LIKE', "%{$this->keyword}%");        
        return view('livewire.konven.memo-pos-check-data')->with(['data'=>$data->paginate(100)]);
    }
    public function keepNew($id)
    {
        \App\Models\KonvenMemo::where('id',$id)->update(['is_temp'=>0]);
        if(\App\Models\KonvenMemo::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
    public function deleteAll()
    {
        \App\Models\KonvenMemo::where('is_temp',1)->delete();
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function keepAll()
    {
        \App\Models\KonvenMemo::where('is_temp',1)->update(['is_temp'=>0]);
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function replaceAll()
    {
        $data = \App\Models\KonvenMemo::where('is_temp',1)->get();
        foreach($data as $child){
            // Check Income
            $income = \App\Models\Income::where(['transaction_table'=>'konven_memo_pos','transaction_id'=>$child->parent_id])->first();
            if($income)
                if($icome->status==2) 
                    continue;
                else 
                    $income->delete(); // delete income
            // Check Expense
            $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_memo_pos','transaction_id'=>$child->parent_id])->first();
            if($expense)
                if($expense->status==2) 
                    continue;
                else $expense->delete(); // delete expense
            // delete parent
            \App\Models\KonvenMemo::find($child->parent_id)->delete();
        }
        \App\Models\KonvenMemo::where('is_temp',1)->update(['is_temp'=>0,'parent_id'=>0]);
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function deleteNew($id)
    {   
        \App\Models\KonvenMemo::find($id)->delete();
        if(\App\Models\KonvenMemo::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
    public function replaceNew($id)
    {
        $child = \App\Models\KonvenMemo::where('id',$id)->first();
        if($child){
            // delete parent
            \App\Models\KonvenMemo::find($child->parent_id)->delete();
            // Check Income
            $income = \App\Models\Income::where(['transaction_table'=>'konven_memo_pos','transaction_id'=>$child->parent_id])->first();
            if($income and $icome->status==1) $income->delete(); // delete income
            // Check Expense
            $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_memo_pos','transaction_id'=>$child->parent_id])->first();
            if($expense and $expense->status==1) $expense->delete(); // delete expense
            $child->is_temp = 0;
            $child->parent_id = 0;
            $child->status = 1;
            $child->save();
        }
        if(\App\Models\KonvenMemo::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
}