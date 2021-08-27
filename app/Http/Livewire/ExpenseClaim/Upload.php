<?php

namespace App\Http\Livewire\ExpenseClaim;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\KonvenClaim;
use App\Models\Expenses;
use App\Models\ExpensePeserta;
use App\Models\Policy;

class Upload extends Component
{
    use WithFileUploads;
    public $file;
    public function render()
    {
        return view('livewire.expense-claim.upload');
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

                if(empty($nomor_polis)) continue;
                $policy = Policy::where('no_polis',$nomor_polis)->first();
                if(!$policy) continue;

                $partisipan = KonvenClaim::where(['nomor_partisipan'=>$nomor_partisipan,'nomor_polis'=>$nomor_polis,'nama_partisipan'=>$nama_partisipan])->first();
                if($partisipan){
                    $partisipan->nilai_klaim = $nilai_klaim;
                    $partisipan->or = $or;
                    $partisipan->reas = $reas;
                    $partisipan->save();

                    $expense = Expenses::where(['transaction_id'=>$partisipan->id,'transaction_table'=>'konven_claim'])->first();
                    if($expense){
                        $expense->payment_amount = $nilai_klaim;
                        $expense->save();
                    }
                }else{
                    $claim = new KonvenClaim();
                    $claim->nomor_polis = $nomor_polis;
                    $claim->nama_pemegang = $nama_pemegang;
                    $claim->nomor_partisipan = $nomor_partisipan;
                    $claim->nama_partisipan = $nama_partisipan;
                    $claim->nilai_klaim = $nilai_klaim;
                    $claim->or = $or;
                    $claim->reas = $reas;
                    $claim->status = $status;
                    $claim->save();
                    
                    // $policy = Policy::where('no_polis',$nomor_polis)->first();
                    // if(!$policy){
                    //     $policy = new Policy();
                    //     $policy->no_polis = $nomor_polis;
                    //     $policy->pemegang_polis = $nama_pemegang;
                    //     $policy->save();
                    // }
    
                    $data = new Expenses();
                    $data->policy_id = isset($policy->id) ? $policy->id : 0;
                    $data->reference_type = 'Claim';
                    $data->recipient = $nomor_polis.' - '. $nama_pemegang;
                    $data->no_voucher = generate_no_voucher_expense();
                    $data->payment_amount = $nilai_klaim;
                    $data->status = 4;
                    $data->user_id = \Auth::user()->id;
                    $data->description = $status;
                    $data->transaction_id = $claim->id;
                    $data->transaction_table = 'konven_claim';
                    $data->save();
                    
                    $peserta = new ExpensePeserta();
                    $peserta->expense_id = $data->id;
                    $peserta->no_peserta = $nomor_partisipan;
                    $peserta->nama_peserta = $nama_partisipan;
                    $peserta->type = 1; // Claim Payable
                    $peserta->policy_id = isset($policy->id) ? $policy->id : 0;
                    $peserta->save();
                }

               
            }
        }
        
        session()->flash('message-success','Upload success !');   
        
        return redirect()->route('expense.claim');
    }
}
