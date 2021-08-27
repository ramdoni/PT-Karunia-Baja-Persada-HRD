<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;

class KomisiSync extends Component
{
    public $total_sync,$is_sync_komisi,$total_finish=0,$data,$total_success=0,$total_failed=0;
    protected $listeners = ['is_sync_komisi'=>'komisi_sync'];
    public function render()
    {
        return view('livewire.konven.komisi-sync');
    }
    public function mount()
    {
        $this->total_sync = \App\Models\KonvenKomisi::where('status',0)->count();
    }
    public function cancel_sync(){
        $this->is_sync_komisi=false;
    }
    public function komisi_sync()
    {
        if($this->is_sync_komisi==false) return false;
        $this->emit('is_sync_komisi');
        foreach(\App\Models\KonvenKomisi::where('status',0)->get() as $key => $item){
            if($key > 1) continue;
            $this->data = '';
            // find data UW
            $uw = \App\Models\KonvenUnderwriting::where('no_kwitansi_debit_note',$item->no_kwitansi)->first();   
            if($uw){
                if($uw->status==1) continue; // jika data UW belum di sinkron
                $item->status=1; //sync
                $item->konven_underwriting_id = $uw->id;
                $this->total_success++;
            }else{
                $this->total_failed++;
                $item->status=2;//Invalid
            }
            $item->save();
            
            $expense = new \App\Models\Expenses();
            $expense->user_id = \Auth::user()->id;
            $expense->no_voucher = generate_no_voucher_income();
            $expense->reference_no = $item->no_kwitansi;
            $expense->reference_date = $item->tgl_invoice;
            $expense->nominal = abs($item->total_komisi);
            $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
            $expense->reference_type = 'Komisi';
            $expense->transaction_id = $item->id;
            $expense->transaction_table = 'konven_komisi';
            $expense->type = 1;
            $expense->save();

            $this->data .=$item->no_kwitansi.'<br />'.$item->no_polis.' / '.$item->pemegang_polis;
            $this->total_finish++;
        }
        if(\App\Models\KonvenKomisi::where('status',0)->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong>');   
            return redirect()->route('konven.underwriting');
        }
    }
}
