<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;
use \App\Models\SyariahReinsurance;
use \App\Models\Policy;

class ReinsuranceSync extends Component
{
    public $total_sync,$is_sync,$total_finish=0,$data='Preparing to synchronize, please wait...!',$total_success=0,$total_failed=0;
    protected $listeners = ['emit_sync'=>'sync'];
    public function render()
    {
        return view('livewire.syariah.reinsurance-sync');
    }
    public function mount()
    {
        $this->total_sync = SyariahReinsurance::where(['status'=>1,'is_temp'=>0])->count();
    }
    public function cancel_sync(){
        $this->is_sync=false;
        $this->emit('refresh-page');
    }
    public function start_sync(){
        $this->is_sync=true;
        $this->sync();
    }
    public function sync()
    {
        if($this->is_sync==false) return false;
        foreach(SyariahReinsurance::where(['status'=>1,'is_temp'=>0])->get() as $key => $item){
            $item->status=2;
            $item->save();
            // cek no polis
            $polis = Policy::where('no_polis',$item->no_polis)->first();
            if(!$polis){
                $polis = new Policy();
                $polis->no_polis = $item->no_polis;
                $polis->pemegang_polis = $item->pemegang_polis;
                $polis->cabang = $item->cabang;
                $polis->alamat = $item->alamat;
                $polis->produk = $item->jenis_produk;
                $polis->type = 2; // syariah
                $polis->is_reas = 1;
                $polis->save();
            }else{
                $polis->is_reas = 1;
                $polis->type = 2; // syariah
                $polis->save();
            }
            
            $this->total_success++;
            $this->total_finish++;
        }
        if(SyariahReinsurance::where(['status'=>1,'is_temp'=>0])->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong> !');   
            return redirect()->route('syariah.reinsurance');
        }
    }
}