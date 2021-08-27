<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SyariahUnderwriting;
use App\Models\Income;

class UnderwritingUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.syariah.underwriting-upload');
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
            $total_double = 0;
            $total_success = 0;
            SyariahUnderwriting::where('is_temp',1)->delete(); // delete data temp
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                foreach($i as $k=>$a){ $i[$k] = trim($a); }
                $bulan = $i[1];
                $user_memo = $i[2];
                $user_akseptasi = $i[3];
                $transaksi_id = $i[4];
                $berkas_akseptasi = $i[5];
                $tanggal_pengajuan_email = $i[6]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[6]) : '';;
                $tanggal_produksi = $i[7]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[7]) : '';;
                $tanggal_akrual = $i[8];
                $bordero = $i[9];
                $no_memo = $i[10];
                $no_debit_note = $i[11];
                $no_polis = $i[12];
                $pemegang_polis = $i[13];
                $alamat = $i[14];
                $cabang = $i[15];
                $jenis_produk = $i[16];
                $jml_kepesertaan_tertunda = $i[17];
                $manfaat_Kepesertaan_tertunda = $i[18];
                $kontribusi_kepesertaan_tertunda = $i[19];
                $jml_kepesertaan = $i[20];
                $no_kepesertaan_awal = $i[21];
                $sd = $i[22];
                $no_kepesertaan_akhir = $i[23];
                $masa_awal_asuransi = $i[24];
                $masa_akhir_asuransi = $i[25];
                $nilai_manfaat = (int)$i[26];
                $dana_tabbaru = (int)$i[27];
                $dana_ujrah = (int)$i[28];
                $kontribusi = (int)$i[29];
                $ektra_kontribusi = (int)$i[30];
                $total_kontribusi = (int)$i[31];
                $pot_langsung = (int)$i[32];
                $jumlah_diskon = abs($i[33]);
                $status_potongan = $i[34];
                $handling_fee = (int)$i[35];
                $jumlah_fee = (int)$i[36];
                $pph = $i[37];
                $jumlah_pph = (int)$i[38];
                $ppn = $i[39];
                $jumlah_ppn = (int)$i[40];
                $biaya_polis = (int)$i[41];
                $biaya_sertifikat = (int)$i[42];
                $extpst = (int)$i[43];
                $net_kontribusi = (int)$i[44];
                $terbilang = $i[45];
                $tgl_update_database = $i[46]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[46]) : '';
                $tgl_update_sistem = $i[47]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[47]) : '';;
                $no_berkas_sistem = $i[48];
                $tgl_posting_sistem = $i[49]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[49]) : '';;
                $ket_posting = $i[50];
                $grace_periode = $i[51];
                $grace_periode_number = $i[52];
                $tgl_jatuh_tempo = $i[53]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[53]) : '';;
                $tgl_lunas = $i[54]?@\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[54]) : '';;
                $pembayaran = (int)$i[55];
                $piutang = (int)$i[56];
                $total_peserta = $i[57];
                $outstanding_peserta = $i[58];
                $produksi_cash_basis = $i[59];
                $ket_lampiran = $i[60];
                $pengeluaran_ujroh = $i[61];
                //  jika tidak ada debit note skip
                if(empty($no_debit_note))continue;

                $find = SyariahUnderwriting::where('no_debit_note',$no_debit_note)->first();
                $data = new SyariahUnderwriting();
                if($find){
                    $income = Income::where(['transaction_table'=>'syariah_underwriting','transaction_id'=>$find->id])->first();
                    if(isset($income) and $income->status==2) continue; // skip jika data sudah di receive

                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }
                $data->bulan = $bulan;
                $data->user_memo = $user_memo;
                $data->user_akseptasi = $user_akseptasi;
                $data->transaksi_id = $transaksi_id;
                $data->berkas_akseptasi = $berkas_akseptasi;
                if($tanggal_pengajuan_email) $data->tanggal_pengajuan_email = date('Y-m-d',$tanggal_pengajuan_email);
                if($tanggal_produksi)$data->tanggal_produksi = date('Y-m-d',($tanggal_produksi));
                if($tanggal_akrual) $data->tanggal_akrual = $tanggal_akrual;
                $data->bordero = $bordero;
                $data->no_memo = $no_memo;
                $data->no_debit_note = $no_debit_note;
                $data->no_polis = $no_polis;
                $data->pemegang_polis = $pemegang_polis;
                $data->alamat = $alamat;
                $data->cabang = $cabang;
                $data->jenis_produk = $jenis_produk;
                $data->jml_kepesertaan_tertunda = $jml_kepesertaan_tertunda;
                $data->manfaat_Kepesertaan_tertunda = $manfaat_Kepesertaan_tertunda;
                $data->kontribusi_kepesertaan_tertunda = $kontribusi_kepesertaan_tertunda;
                $data->jml_kepesertaan = $jml_kepesertaan;
                $data->no_kepesertaan_awal = $no_kepesertaan_awal;
                $data->no_kepesertaan_akhir = $no_kepesertaan_akhir;
                $data->masa_awal_asuransi = $masa_awal_asuransi;
                $data->masa_akhir_asuransi = $masa_akhir_asuransi;
                $data->nilai_manfaat = $nilai_manfaat;
                $data->dana_tabbaru = $dana_tabbaru;
                $data->dana_ujrah = $dana_ujrah;
                $data->kontribusi = $kontribusi;
                $data->ektra_kontribusi = $ektra_kontribusi;
                $data->total_kontribusi = $total_kontribusi;
                $data->pot_langsung = $pot_langsung;
                $data->jumlah_diskon = $jumlah_diskon;
                $data->status_potongan = $status_potongan;
                $data->handling_fee = $handling_fee;
                $data->jumlah_fee = $jumlah_fee;
                $data->pph = $pph;
                $data->jumlah_pph = $jumlah_pph;
                $data->ppn = $ppn;
                $data->jumlah_ppn = $jumlah_ppn;
                $data->biaya_polis = $biaya_polis;
                $data->biaya_sertifikat = $biaya_sertifikat;
                $data->extpst = $extpst;
                $data->net_kontribusi = $net_kontribusi;
                $data->terbilang = $terbilang;
                if($tgl_update_database) $data->tgl_update_database = date('Y-m-d',($tgl_update_database));
                if($tgl_update_sistem) $data->tgl_update_sistem = date('Y-m-d',($tgl_update_sistem));
                $data->no_berkas_sistem = $no_berkas_sistem;
                if($tgl_posting_sistem) $data->tgl_posting_sistem = date('Y-m-d',($tgl_posting_sistem));
                $data->ket_posting = $ket_posting;
                $data->grace_periode = $grace_periode;
                $data->grace_periode_number = $grace_periode_number;
                if($tgl_jatuh_tempo) $data->tgl_jatuh_tempo = date('Y-m-d',($tgl_jatuh_tempo));
                if($tgl_lunas) $data->tgl_lunas = date('Y-m-d',($tgl_lunas));
                $data->pembayaran = $pembayaran;
                $data->piutang = $piutang;
                $data->total_peserta = $total_peserta;
                $data->outstanding_peserta = $outstanding_peserta;
                $data->produksi_cash_basis = $produksi_cash_basis;
                $data->ket_lampiran = $ket_lampiran;
                $data->pengeluaran_ujroh = $pengeluaran_ujroh;
                $data->status = 1;
                $data->user_id = \Auth::user()->id;
                $data->save();
                $total_success++;
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data-underwriting');
        else{
            session()->flash('message-success','Upload success, Success Upload <strong>'. $total_success.'</strong>, Double Data :<strong>'. $total_double.'</strong>');   
            return redirect()->route('syariah.underwriting');
        }
    }
}
