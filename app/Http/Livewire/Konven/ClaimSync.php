<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;

class ClaimSync extends Component
{
    public $total_sync,$is_sync,$total_finish=0,$data;
    protected $listeners = ['is_sync'=>'claim_sync'];
    public function render()
    {
        return view('livewire.konven.claim-sync');
    }
    public function mount()
    {
        $this->total_sync = \App\Models\KonvenClaim::where('status_claim',1)->count();
    }
    public function cancel_sync(){
        $this->is_sync=false;
    }
    public function claim_sync()
    {
        if($this->is_sync==false) return false;
        $this->emit('is_sync');

        foreach(\App\Models\KonvenClaim::where('status_claim',1)->get() as $key => $item){
            if($key > 1) continue;
            // find at table Underwriting
            $find_uw = \App\Models\KonvenUnderwriting::where('no_polis',$item->nomor_polis)->first();

            if(!$find_uw){
                $item->status_claim = 3; // nomor polis tidak ditemukan
            }else{
                $item->status_claim = 2;
                // find nomor peserta
                $total_peserta = $find_uw->jumlah_peserta;
                $nomor_peserta_awal = $find_uw->nomor_peserta_awal;
                $nomor_peserta_akhir = $find_uw->nomor_peserta_akhir;
                $this->data = "<strong>". $item->nama_pemegang. "({$item->nomor_polis})</strong>"
                                .'<br > Partisipan : '. $item->nama_partisipan ."{$item->nomor_partisipan}";
                $data = new \App\Models\Expenses();
                $data->no_voucher = generate_no_voucher_expense();
                $data->recipient = $item->nomor_partisipan.' / '.$item->nama_partisipan;
                $data->user_id = \Auth::user()->id;
                $data->reference_type = 'Claim';
                $data->nominal = $item->nilai_klaim;
                $data->reference_no = $item->nomor_partisipan;
                $data->payment_amount = 0;
                $data->transaction_table = 'konven_claim';
                $data->transaction_id = $item->id;
                $data->type = 1;
                $data->save();
            }
            $item->save();
            
            $this->total_finish++;
        }
        if(\App\Models\KonvenClaim::where('status_claim',1)->count()==0){
            session()->flash('message-success','Synchronize success, Total Success '.$this->total_success.', Total Failed <strong>'.$this->total_failed.'</strong> !');   
            return redirect()->route('konven.claim');
        }
    }
}
