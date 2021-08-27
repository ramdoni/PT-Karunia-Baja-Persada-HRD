<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class RefundSync extends Component
{
    public $total_sync,$is_sync,$total_finish=0,$data,$total_success=0,$total_failed=0;
    protected $listeners = ['is_sync_refund'=>'sync'];
    public function render()
    {
        return view('livewire.syariah.refund-sync');
    }
    public function mount()
    {
        $this->total_sync = \App\Models\SyariahRefund::where('status',0)->count();
    }
    public function cancel_sync(){
        $this->is_sync=false;
    }
    public function sync()
    {
        if($this->is_sync==false) return false;
        $this->emit('is_sync_refund');
        foreach(\App\Models\SyariahRefund::where(['status'=>0,'is_temp'=>0])->get() as $key => $item){
            if($key > 1) continue;
            $this->data = $item->no_debit_note_terakseptasi."<br />";
            // find data UW
            $uw = \App\Models\SyariahUnderwriting::where('no_debit_note',$item->no_debit_note_terakseptasi)->first();   
            if($uw){
                if($uw->status==1) continue; // jika data UW belum di sinkron
                $item->status=1; //sync
                $item->syariah_underwriting_id = $uw->id;
                $this->total_success++;
            }else{
                $this->total_failed++;
                $item->status=2;//Invalid
            }
            $item->save();
            $this->total_finish++;
            if(!$uw) continue; // Skip jika tidak ditemukan data UW
            $bank = \App\Models\BankAccount::where('no_rekening',replace_idr($item->no_rekening))->first();
            if(!$bank){
                $bank = new \App\models\BankAccount();
                $bank->bank = $item->bank;
                $bank->no_rekening = replace_idr($item->no_rekening);
                $bank->owner = $item->tujuan_pembayaran;
                $bank->save();
            }

            $expense = new \App\Models\Expenses();
            $expense->user_id = \Auth::user()->id;
            $expense->no_voucher = generate_no_voucher_income();
            $expense->reference_no = $item->no_credit_note;
            $expense->reference_date = $item->tgl_refund;
            $expense->nominal = $item->refund_kontribusi;
            $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
            $expense->reference_type = 'Refund';
            $expense->transaction_id = $item->id;
            $expense->transaction_table = 'syariah_refund';
            $expense->description = $item->ket;
            $expense->rekening_bank_id = $bank->id;
            $expense->type = 2;
            $expense->save();
            
            $this->data .=$item->no_polis.' / '.$item->pemegang_polis;
        }
        if(\App\Models\SyariahRefund::where('status',0)->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong>');   
            $this->emit('refresh-page');
        }
    }
}
