<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
class ReinsuranceUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.konven.reinsurance-upload');
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
            // Delete Data Temporary
            \App\Models\KonvenReinsurance::where('is_temp',1)->delete();
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                
                foreach($i as $k=>$a){$i[$k] = trim($a);}
                
                $no_polis = $i[1];
                $pemegang_polis = $i[2];
                $peserta = $i[3];
                $uang_pertanggungan = $i[4];
                $uang_pertanggungan_reas = $i[5];
                $premi_gross_ajri = $i[6];
                $premi_reas = $i[7];
                $komisi_reinsurance = $i[8];
                $premi_reas_netto = $i[9];
                $keterangan = $i[10];
                $kirim_reas = $i[11];
                $broker_re = $i[12];
                $reasuradur = $i[13];
                $bulan = $i[14];
                $ekawarsa_jangkawarsa = $i[15];
                $produk = $i[16];

                $data = new \App\Models\KonvenReinsurance();
                $find = \App\Models\KonvenReinsurance::where('no_polis',$no_polis)->first();
                if($find){
                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }else $total_success++;

                $data->no_polis = $no_polis; 
                $data->pemegang_polis = $pemegang_polis;
                $data->peserta = $peserta;
                $data->uang_pertanggungan = $uang_pertanggungan;
                $data->uang_pertanggungan_reas = $uang_pertanggungan_reas;
                $data->premi_gross_ajri = $premi_gross_ajri;
                $data->premi_reas = $premi_reas;
                $data->komisi_reinsurance = $komisi_reinsurance;
                $data->premi_reas_netto = $premi_reas_netto;
                $data->keterangan = $keterangan;
                $data->kirim_reas = $kirim_reas;
                $data->broker_re = $broker_re;
                $data->reasuradur = $reasuradur;
                $data->bulan = $bulan;
                $data->ekawarsa_jangkawarsa = $ekawarsa_jangkawarsa;
                $data->produk = $produk;
                $data->status = 1;
                $data->save();
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data');
        else{
            session()->flash('message-success','Upload success !');   
            return redirect()->route('konven.reinsurance');
        }
    }
}
