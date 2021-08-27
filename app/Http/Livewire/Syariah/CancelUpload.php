<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithFileUploads;

class CancelUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.syariah.cancel-upload');
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
            \App\Models\SyariahCancel::where('is_temp',1)->delete(); // delete data temp
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                foreach($i as $k=>$a){ $i[$k] = trim($a); }
                
                $find = \App\Models\SyariahCancel::where('no_credit_note',$i[8])->first();
                $data = new \App\Models\SyariahCancel();
                if($find){
                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }else $data->is_temp = 0;
                $data->bulan = $i[1];
                $data->user_memo = $i[2];
                $data->user_akseptasi = $i[3];
                $data->no_berkas = $i[4];
                if($i[5]) $data->tgl_pengajuan_email = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[5]));
                if($i[6]) $data->tgl_cancel = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[6]));
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
                $data->jml_manfaat_kepesertaan_tertunda = $i[18];
                $data->jml_kontribusi_kepesertaan_tertunda = $i[19];
                $data->jumlah_kepesertaan = $i[20];
                $data->no_kepesertaan_awal = $i[21];
                $data->no_kepesertaan_akhir = $i[23];
                if($i[24]) $data->masa_awal = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[24]));
                if($i[25]) $data->masa_akhir = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[25]));
                $data->manfaat_cancel = replace_idr($i[26]);
                $data->kontribusi_gross_cancel = replace_idr($i[27]);
                $data->ektra_kontribusi_cancel = replace_idr($i[28]);
                $data->diskon_kontribusi = $i[29];
                $data->jumlah_diskon = replace_idr($i[30]);
                $data->ppn = $i[31];
                $data->jumlah_ppn = replace_idr($i[32]);
                $data->fee = replace_idr($i[33]);
                $data->jumlah_handling_fee = replace_idr($i[34]);
                $data->pph = $i[35];
                $data->jumlah_pph = replace_idr($i[36]);
                $data->refund = replace_idr($i[37]);
                $data->terbilang = $i[38];
                $data->grace_periode = $i[39];
                $data->grace_periode_num = $i[40];
                if($i[41]) $data->tgl_jatoh_tempo = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[41]));
                if($i[42]) $data->tgl_update_database = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[42]));
                $data->no_debit_note = $i[43];
                if($i[44]) $data->tgl_debit_note = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[44]));
                $data->kontribusi_debit_note = $i[45];
                if($i[46]) $data->tgl_bayar_refund = date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[46]));
                $data->ket = $i[47];
                $data->status = 0;
                $data->user_id = \Auth::user()->id;
                $data->save();
                $total_success++;
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data-cancel');
        else{
            session()->flash('message-success','Upload success, Success Upload <strong>'. $total_success.'</strong>, Double Data :<strong>'. $total_double.'</strong>');   
            $this->emit('refresh-page');
        }
    }
}
