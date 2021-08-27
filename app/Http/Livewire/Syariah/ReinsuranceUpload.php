<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SyariahReinsurance;

class ReinsuranceUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.syariah.reinsurance-upload');
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
            SyariahReinsurance::where('is_temp',1)->delete(); // delete data temp
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                if(empty($i[1])) continue; // skip jika ada nomor polis kosong
                foreach($i as $k=>$a){ $i[$k] = trim($a); }
                $find = SyariahReinsurance::where('no_polis',$i[1])->first();
                $data = new SyariahReinsurance();
                if($find){
                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }
                $data->bulan = $i[0];
                $data->no_polis = $i[1];
                $data->pemegang_polis = $i[2];
                $data->peserta = $i[3];
                $data->nilai_manfaat_asuransi_total = $i[4];
                $data->nilai_manfaat_asuransi_or = $i[5];
                $data->nilai_manfaat_asuransi_reas = $i[6];
                $data->kontribusi_ajri = $i[7];
                $data->kontribusi_reas_gross = $i[8];
                $data->ujroh = $i[9];
                $data->em = $i[10];
                $data->kontribusi_reas_netto = $i[11];
                $data->keterangan = $i[12];
                $data->kirim_reas = $i[13];
                $data->broker_re_reasuradur = $i[14];
                $data->reasuradur = $i[15];
                $data->ekawarsa_jangkawarsa = $i[16];
                $data->tetap_menurun = $i[17];
                $data->produk = $i[18];
                if(!empty($i[19])) $data->tgl_bayar = @date('Y-m-d',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[19]));
                $data->status =1;
                $data->save();
                $total_success++;
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data');
        else{
            session()->flash('message-success','Upload success, Success Upload <strong>'. $total_success.'</strong>, Double Data :<strong>'. $total_double.'</strong>');   
            return redirect()->route('syariah.reinsurance');
        }
    }
}
