<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithFileUploads;

class RefundUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.syariah.refund-upload');
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
            \App\Models\SyariahRefund::where('is_temp',1)->delete(); // delete data temp
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                foreach($i as $k=>$a){ $i[$k] = trim($a); }
                
                $find = \App\Models\SyariahRefund::where('no_credit_note',$i[8])->first();
                $data = new \App\Models\SyariahRefund();
                if($find){
                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }else $data->is_temp = 0;
                $data->bulan = $i[1];
                $data->user_memo = $i[2];
                $data->user_akseptasi = $i[3];
                $data->berkas_akseptasi = $i[4];
                if($i[5]) $data->tgl_pengajuan_email = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[5]));
                if($i[6]) $data->tgl_refund = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[6]));
                $data->no_memo = $i[7];
                $data->no_credit_note = $i[8];
                $data->no_polis = $i[9];
                $data->pemegang_polis = $i[10];
                $data->alamat = $i[11];
                $data->cabang = $i[12];
                $data->jenis_produk = $i[13];
                $data->tujuan_pembayaran = $i[14];
                $data->bank = $i[15];
                $data->no_rekening = $i[16];
                $data->jml_kepesertaan_tertunda = $i[17];
                $data->manfaat_kepesertaan_tertunda = (int)$i[18];
                $data->kontribusi_kepesertaan_tertunda = (int)$i[19];
                $data->jumlah_kepesertaan = $i[20];
                $data->no_kepesertaan_awal = $i[21];
                $data->no_kepesertaan_akhir = $i[23];
                if($i[24]) $data->masa_awal = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[24]));
                if($i[25]) $data->masa_akhir = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[25]));
                if($i[26]) $data->tgl_produksi = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[26]));
                $data->no_debit_note_terakseptasi = $i[27];
                $data->kontribusi_dn = (int)$i[28];
                $data->manfaat_asuransi = (int)$i[29];
                $data->kontribusi = (int)$i[30];
                $data->refund_kontribusi = (int)$i[31];
                $data->terbilang = $i[32];
                $data->ket_lampiran = $i[33];
                $data->grace_periode = $i[34];
                $data->grace_periode_num = $i[35];
                if($i[36]) $data->tgl_jatoh_tempo = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[36]));
                if($i[37]) $data->tgl_update_database = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[37]));
                if($i[38]) $data->tgl_bayar = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[38]));
                $data->ket = $i[39];
                $data->ket_2 = $i[40];
                $data->ket_reas = $i[41];
                if($i[42]) $data->tgl_bayar_reas = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[42]));
                $data->status = 0;
                $data->user_id = \Auth::user()->id;
                $data->save();
                $total_success++;
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data-refund');
        else{
            $this->emit('message-success','Upload success, Success Upload <strong>'. $total_success.'</strong>, Double Data :<strong>'. $total_double.'</strong>');   
            $this->emit('refresh-page');
        }
    }
}
