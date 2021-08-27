<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
class KomisiUpload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.konven.komisi-upload');
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
            $total_success = 0;
            $total_double = 0;
            \App\Models\KonvenKomisi::where('is_temp',1)->delete();
            foreach($sheetData as $key => $i){
                if($key<1) continue; // skip header
                
                foreach($i as $k=>$a){$i[$k] = trim($a);}
                // find data exist
                $find = \App\Models\KonvenKomisi::where(['no_kwitansi'=>$i[9],'status'=>1])->first();
                if($find){
                    $total_double++;
                    continue;
                }
                $total_success++;
                $user = $i[1];
                //$tgl_memo = (int)$i[2]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[2]):'';
                $tgl_memo = $i[2];
                $no_reg = $i[3];
                $no_polis = $i[4];
                $no_polis_sistem = $i[5];
                $pemegang_polis = $i[6];
                $produk = $i[7];
                //$tgl_invoice = (int)$i[8]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[8]):'';
                $tgl_invoice = $i[8];
                $no_kwitansi = $i[9];
                $total_peserta = $i[10];
                $no_peserta = $i[11];
                $total_up = $i[12];
                $total_premi_gross = $i[13];
                $em = $i[14];
                $disc_pot_langsung = $i[15];
                $premi_netto_yg_dibayarkan = $i[16];
                $perkalian_biaya_penutupan = $i[17];
                $fee_base = $i[18];
                $biaya_fee_base = $i[19];
                $maintenance = $i[20];
                $biaya_maintenance = $i[21];
                $admin_agency = $i[22];
                $biaya_admin_agency = $i[23];
                $agen_penutup = $i[24];
                $biaya_agen_penutup = $i[25];
                $operasional_agency = $i[26];
                $biaya_operasional_agency = $i[27];
                $handling_fee_broker = $i[28];
                $biaya_handling_fee_broker = $i[29];
                $referral_fee = $i[30];
                $biaya_rf = $i[31];
                $pph = $i[32];
                $jumlah_pph = $i[33];
                $ppn = $i[34];
                $jumlah_ppn = $i[35];
                $cadangan_klaim = $i[36];
                $jml_cadangan_klaim = $i[37];
                $klaim_kematian = $i[38];
                $pembatalan = $i[39];
                $total_komisi = $i[40];
                $tujuan_pembayaran = $i[41];
                $no_rekening = $i[42];
                //$tgl_lunas = (int)$i[43]?\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($i[43]):'';
                $tgl_lunas = $i[43];
                $find = \App\Models\KonvenKomisi::where(['no_kwitansi'=>$i[9]])->first();
                $data = new \App\Models\KonvenKomisi();
                if($find){
                    if($find->status==1){
                        $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_komisi','transaction_id'=>$find->id])->first();
                        if($expense and $expense->status ==2) continue; // jika komisi sudah di proses oleh finance di skip
                    }
                    $data->is_temp = 1;
                    $data->parent_id = $find->id;
                    $total_double++;
                }else $total_success++;

                $data->status = 0;
                $data->user = $user;
                if($tgl_memo) $data->tgl_memo = date('Y-m-d',strtotime($tgl_memo));
                $data->no_reg = $no_reg; 
                $data->no_polis = $no_polis; 
                $data->no_polis_sistem = $no_polis_sistem; 
                $data->pemegang_polis = $pemegang_polis; 
                $data->produk = $produk; 
                if($tgl_invoice) $data->tgl_invoice = date('Y-m-d',strtotime($tgl_invoice));
                $data->no_kwitansi = $no_kwitansi; 
                $data->total_peserta = $total_peserta; 
                $data->no_peserta = $no_peserta; 
                $data->total_up = $total_up; 
                $data->total_premi_gross = $total_premi_gross; 
                $data->em = $em; 
                $data->disc_pot_langsung = $disc_pot_langsung; 
                $data->premi_netto_yg_dibayarkan = $premi_netto_yg_dibayarkan; 
                $data->perkalian_biaya_penutupan = $perkalian_biaya_penutupan; 
                $data->fee_base = $fee_base; 
                $data->biaya_fee_base = $biaya_fee_base; 
                $data->maintenance = $maintenance; 
                $data->biaya_maintenance = $biaya_maintenance; 
                $data->admin_agency = $admin_agency; 
                $data->biaya_admin_agency = $biaya_admin_agency; 
                $data->agen_penutup = $agen_penutup; 
                $data->biaya_agen_penutup = $biaya_agen_penutup; 
                $data->operasional_agency = $operasional_agency; 
                $data->biaya_operasional_agency = $biaya_operasional_agency; 
                $data->handling_fee_broker = $handling_fee_broker; 
                $data->biaya_handling_fee_broker = $biaya_handling_fee_broker; 
                $data->referral_fee = $referral_fee; 
                $data->biaya_rf = $biaya_rf; 
                $data->pph = $pph; 
                $data->jumlah_pph = $jumlah_pph; 
                $data->ppn = $ppn; 
                $data->jumlah_ppn = $jumlah_ppn; 
                $data->cadangan_klaim = $cadangan_klaim; 
                $data->jml_cadangan_klaim = $jml_cadangan_klaim; 
                $data->klaim_kematian = $klaim_kematian; 
                $data->pembatalan = $pembatalan; 
                $data->total_komisi = $total_komisi; 
                $data->tujuan_pembayaran = $tujuan_pembayaran; 
                $data->no_rekening = $no_rekening; 
                if($tgl_lunas) $data->tgl_lunas = date('Y-m-d',strtotime($tgl_lunas));
                $data->status = 0;
                $data->save();
            }
        }
        if($total_double>0)
            $this->emit('emit-check-data-komisi');
        else{
            session()->flash('message-success','Upload success, Total Success <strong>'.$total_success.'</strong>, Total Double <strong>'.$total_double.'</strong> !');   
            return redirect()->route('konven.underwriting');
        }
    }
}
