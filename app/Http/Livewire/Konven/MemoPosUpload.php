<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\KonvenMemo;
use App\Models\Income;
use App\Models\Expenses;

class MemoPosUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.konven.memo-pos-upload');
    }

    public function save()
    {
        $this->validate([
            'file'=>'required|mimes:xls,xlsx|max:51200' // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
       
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data = $reader->load($path);
        $sheetData = $data->getActiveSheet()->toArray();
        
        if(count($sheetData) > 0){
            $countLimit = 1;
            $total_success = 0;
            $total_double = 0;
            // Delete data temporary
            KonvenMemo::where('is_temp',1)->delete();
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                
                foreach($i as $k=>$a){$i[$k] = trim($a);}
                
                $bulan = $i[1];
                $user = $i[2];
                $user_akseptasi = $i[3];
                $berkas_akseptasi = $i[4];
                $tgl_pengajuan_email = $i[5] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[5]) : '';
                $tgl_produksi= $i[6] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[6]) :'';
                $no_reg = $i[7];
                $no_reg_sistem = $i[8];
                $no_dn_cn = $i[9];
                $no_dn_cn_sistem = $i[10];
                $jenis_po = $i[11];
                $status = $i[12];
                $posting = $i[13];
                $jenis_po_2 = $i[14];
                $ket_perubahan1 = $i[15];
                $ket_perubahan2 = $i[16];
                $no_polis = $i[17];
                $no_polis_sistem = $i[18];
                $pemegang_polis = $i[19];
                $cabang = $i[20];
                $produk = $i[21];
                $alamat = $i[22];
                $up_tujuan_surat = $i[23];
                $tujuan_pembayaran = $i[24];
                $bank = $i[25];
                $no_rekening = $i[26];
                $jumlah_peserta_pending = $i[27];
                $up_peserta_pending = $i[28];
                $premi_peserta_pending = $i[29];
                $peserta = $i[30];
                $no_peserta_awal = $i[31];
                $no_peserta_akhir = $i[33];
                $no_sertifikat_awal = $i[34];
                $no_sertifikat_akhir = $i[35];
                $periode_awal = $i[36] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[36]):'';
                $periode_akhir = $i[37] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[37]):'';
                $tgl_proses = $i[38]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[38]):'';
                $movement = $i[39];
                $tgl_invoice = $i[40]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[40]):'';
                $tgl_invoice2 = $i[41]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[41]):'';
                $no_kwitansi_finance = $i[42];
                $no_kwitansi_finance2 = $i[43];
                $total_gross_kwitansi = $i[44];
                $total_gross_kwitansi2 = $i[45];
                $jumlah_peserta_update = $i[46];
                $up_cancel = $i[47];
                $premi_gross_cancel	 = $i[48];
                $extra_premi = $i[49];
                $extextra = $i[50];
                $rpextra = $i[51];
                $diskon_premi = $i[52];
                $jml_diskon = replace_idr($i[53]);
                $rp_diskon = $i[54];
                $extdiskon = $i[55];
                $fee = $i[56];
                $jml_handling_fee = $i[57];
                $ext_fee = $i[58];
                $rp_fee = $i[59];
                $tampilan_fee = $i[60];
                $pph = $i[61];
                $jml_pph = $i[62];
                $extpph = $i[63];
                $rppph = $i[64];
                $ppn = $i[65];
                $jml_ppn = $i[66];
                $extppn = $i[67];
                $rpppn = $i[68];
                $biaya_sertifikat = $i[69];
                $extbiayasertifikat = $i[70];
                $rpbiayasertifikat = $i[71];
                $extpstsertifikat = $i[72];
                $net_sblm_endors = $i[73];
                $data_stlh_endors = $i[74];
                $up_stlh_endors = $i[75];
                $premi_gross_endors = $i[76];
                $extra_premi2 = $i[77];
                $extem = $i[78];
                $rpxtra = $i[79];
                $discount = $i[80];
                $jml_discount = $i[81];
                $ext_discount = $i[82];
                $rpdiscount = $i[83];
                $handling_fee = $i[84];
                $jml_fee = $i[85];
                $extfee = $i[86];
                $rpfee = $i[87];
                $tampilanfee = $i[88];
                $pph2 = $i[89];
                $jml_pph2 = $i[90];
                $extpph2 = $i[91];
                $rppph2 = $i[92];
                $ppn2 = $i[93];
                $jml_ppn2 = $i[94];
                $extppn2 = $i[95];
                $rpppn2 = $i[96];
                $biaya_sertifikat2 = $i[97];
                $extbiayasertifikat2 = $i[98];
                $rpbiayasertifikat2 = $i[99];
                $extpstsertifikat2 = $i[100];
                $net_stlh_endors = $i[101];
                $refund = $i[102];
                $terbilang = $i[103];
                $ket_lampiran = $i[104];
                $grace_periode = $i[105];
                $grace_periode_nominal = $i[106];
                $tgl_jatuh_tempo = $i[107]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[107]):'';
                $tgl_update_database = $i[108]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[108]):'';
                $tgl_update_sistem = $i[109]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[109]):'';
                $no_berkas_sistem = $i[110];
                $tgl_posting_sistem = $i[111]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[111]):'';
                $no_debit_note_finance = $i[112];
                $tgl_bayar = $i[113]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[113]):'';
                $ket = $i[114];
                $tgl_output_email = $i[115]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((int)$i[115]):'';
                $no_berkas2 = $i[116];

                // find data exitst 
                $find = KonvenMemo::where(['no_dn_cn'=>$i[9]])->first();
                $data = new KonvenMemo();
                if($find){
                    if($find->status_sync==1) {
                        // Check Income
                        $income = Income::where(['transaction_table'=>'konven_memo_pos','transaction_id'=>$find->id])->first();
                        if($income and $icome->status==2) continue; // jika income sudah di proses maka di skip
                        // Check Expense
                        $expense = Expenses::where(['transaction_table'=>'konven_memo_pos','transaction_id'=>$find->id])->first();
                        if($expense and $expense->status==2) continue; // jika expense sudah di proses maka di skip
                    }                    
                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }else $total_double++;
                
                $data->status_sync = 0;
                $data->bulan = $bulan;
                $data->user = $user;
                $data->user_akseptasi = $user_akseptasi;
                $data->berkas_akseptasi = $berkas_akseptasi;
                if($tgl_pengajuan_email) $data->tgl_pengajuan_email = date('Y-m-d',($tgl_pengajuan_email));
                if($tgl_produksi) $data->tgl_produksi = date('Y-m-d',($tgl_produksi));
                $data->no_reg = $no_reg;
                $data->no_reg_sistem = $no_reg_sistem;
                $data->no_dn_cn = $no_dn_cn;
                $data->no_dn_cn_sistem = $no_dn_cn_sistem;
                $data->jenis_po = $jenis_po;
                $data->status = $status;
                $data->posting = $posting;
                $data->jenis_po_2 = $jenis_po_2;
                $data->ket_perubahan1 = $ket_perubahan1;
                $data->ket_perubahan2 = $ket_perubahan2;
                $data->no_polis = $no_polis;
                $data->pemegang_polis = $pemegang_polis;
                $data->cabang = $cabang;
                $data->produk = $produk;
                $data->alamat = $alamat;
                $data->up_tujuan_surat = $up_tujuan_surat;
                $data->tujuan_pembayaran = $tujuan_pembayaran;
                $data->bank = $bank;
                $data->no_rekening = $no_rekening;
                $data->jumlah_peserta_pending = $jumlah_peserta_pending;
                $data->up_peserta_pending = $up_peserta_pending;
                $data->premi_peserta_pending = $premi_peserta_pending;
                $data->peserta = $peserta;
                $data->no_peserta_awal = $no_peserta_awal;
                $data->no_peserta_akhir = $no_peserta_akhir;
                $data->no_sertifikat_awal = $no_sertifikat_awal;
                $data->no_sertifikat_akhir = $no_sertifikat_akhir;
                if($periode_awal)
                    $data->periode_awal = date('Y-m-d',($periode_awal));    
                if($periode_akhir)
                    $data->periode_akhir = date('Y-m-d',($periode_akhir));
                if($tgl_proses)
                    $data->tgl_proses = date('Y-m-d',($tgl_proses));
                $data->movement = $movement;
                if($tgl_invoice)
                    $data->tgl_invoice = date('Y-m-d',($tgl_invoice));
                if($tgl_invoice2)
                    $data->tgl_invoice2 = date('Y-m-d',($tgl_invoice2));
                $data->no_kwitansi_finance = $no_kwitansi_finance;
                $data->no_kwitansi_finance2 = $no_kwitansi_finance2;
                $data->total_gross_kwitansi = $total_gross_kwitansi;
                $data->total_gross_kwitansi2 = $total_gross_kwitansi2;
                $data->jumlah_peserta_update = $jumlah_peserta_update;
                $data->up_cancel = $up_cancel;
                $data->premi_gross_cancel = $premi_gross_cancel;
                $data->extra_premi = $extra_premi;
                $data->extextra = $extextra;
                $data->rpextra = $rpextra;
                $data->diskon_premi = $diskon_premi;
                $data->jml_diskon = $jml_diskon;
                $data->rp_diskon = $rp_diskon;
                $data->extdiskon = $extdiskon;
                $data->fee = $fee;
                $data->jml_handling_fee = $jml_handling_fee;
                $data->ext_fee = $ext_fee;
                $data->rp_fee = $rp_fee;
                $data->tampilan_fee = $tampilan_fee;
                $data->pph = $pph;
                $data->jml_pph = $jml_pph;
                $data->extpph = $extpph;
                $data->rppph = $rppph;
                $data->ppn = $ppn;
                $data->jml_ppn = $jml_ppn;
                $data->extppn = $extppn;
                $data->rpppn = $rpppn;
                $data->biaya_sertifikat = $biaya_sertifikat;
                $data->extbiayasertifikat = $extbiayasertifikat;
                $data->rpbiayasertifikat = $rpbiayasertifikat;
                $data->extpstsertifikat = $extpstsertifikat;
                $data->net_sblm_endors = $net_sblm_endors;
                $data->data_stlh_endors = $data_stlh_endors;
                $data->up_stlh_endors = $up_stlh_endors;
                $data->premi_gross_endors = $premi_gross_endors;
                $data->extra_premi2 = $extra_premi2;
                $data->extem = $extem;
                $data->rpxtra = $rpxtra;
                $data->discount = $discount;
                $data->jml_discount = $jml_discount;
                $data->ext_discount = $ext_discount;
                $data->rpdiscount = $rpdiscount;
                $data->handling_fee = $handling_fee;
                $data->jml_fee = $jml_fee;
                $data->extfee = $extfee;
                $data->rpfee = $rpfee;
                $data->tampilanfee = $tampilanfee;
                $data->pph2 = $pph2;
                $data->jml_pph2 = $jml_pph2;
                $data->extpph2 = $extpph2;
                $data->rppph2 = $rppph2;
                $data->ppn2 = $ppn2;
                $data->jml_ppn2 = $jml_ppn2;
                $data->extppn2 = $extppn2;
                $data->rpppn2 = $rpppn2;
                $data->biaya_sertifikat2 = $biaya_sertifikat2;
                $data->extbiayasertifikat2 = $extbiayasertifikat2;
                $data->rpbiayasertifikat2 = $rpbiayasertifikat2;
                $data->extpstsertifikat2 = $extpstsertifikat2;
                $data->net_stlh_endors = $net_stlh_endors;
                $data->refund = $refund;
                $data->terbilang = $terbilang;
                $data->ket_lampiran = $ket_lampiran;
                $data->grace_periode = $grace_periode;
                $data->grace_periode_nominal = $grace_periode_nominal;
                if($tgl_jatuh_tempo)
                    $data->tgl_jatuh_tempo = date('Y-m-d',($tgl_jatuh_tempo));
                if($tgl_update_database)
                    $data->tgl_update_database = date('Y-m-d',($tgl_update_database));
                if($tgl_update_sistem)
                    $data->tgl_update_sistem = date('Y-m-d',($tgl_update_sistem));
                $data->no_berkas_sistem = $no_berkas_sistem;
                if($tgl_posting_sistem) $data->tgl_posting_sistem = date('Y-m-d',($tgl_posting_sistem));
                $data->no_debit_note_finance = $no_debit_note_finance;
                if($tgl_bayar)
                    $data->tgl_bayar = date('Y-m-d',($tgl_bayar));
                $data->ket = $ket;
                if($tgl_output_email)
                    $data->tgl_output_email = date('Y-m-d',($tgl_output_email));
                $data->no_berkas2 = $no_berkas2;
                $data->save();
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data-memo-pos');
        else{
            session()->flash('message-success','Upload success, Total Succes <strong>'.$total_success.'</strong>, Total Double <strong>'.$total_double.'</strong> !');   
            return redirect()->route('konven.underwriting');
        }
    }
}
