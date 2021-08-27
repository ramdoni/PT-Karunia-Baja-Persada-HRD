<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
class ClaimUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.konven.claim-upload');
    }

    public function save()
    {
        $this->validate([
            'file'=>'required|mimes:xls,xlsx|max:51200' // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
       
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $data = $reader->load($path);
        $sheetData = $data->getActiveSheet()->toArray();
        
        if(count($sheetData) > 0){
            $countLimit = 1;
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                
                foreach($i as $k=>$a){$i[$k] = trim($a);}

                $nomor_polis = $i[0];
                $nama_pemegang = $i[1];
                $nomor_partisipan = $i[2];
                $nama_partisipan = $i[3];
                $nilai_klaim = $i[4];
                $or = $i[5];
                $reas = $i[6];
                $status = $i[7];

                $data = new \App\Models\KonvenClaim();
                $data->nomor_polis = $nomor_polis;
                $data->nama_pemegang = $nama_pemegang;
                $data->nomor_partisipan = $nomor_partisipan;
                $data->nama_partisipan = $nama_partisipan;
                $data->nilai_klaim = $nilai_klaim;
                $data->or = $or;
                $data->reas = $reas;
                $data->status = $status;
                $data->save();
            }
        }
        session()->flash('message-success','Upload success !');   
        return redirect()->route('konven.claim');
    }
}
