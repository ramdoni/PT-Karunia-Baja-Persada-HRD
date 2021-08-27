<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class CancelSync extends Component
{
    public $total_sync,$is_sync_cancel,$total_finish=0,$data,$total_success=0,$total_failed=0;
    protected $listeners = ['emit_sync_cancel'=>'sync'];
    public function render()
    {
        return view('livewire.syariah.cancel-sync');
    }
    public function mount()
    {
        $this->total_sync = \App\Models\SyariahCancel::where('status',0)->count();
    }
    public function cancel_sync(){
        $this->is_sync_cancel=false;
    }
    public function sync()
    {
        if($this->is_sync_cancel==false) return false;
        $this->emit('emit_sync_cancel');
        foreach(\App\Models\SyariahCancel::where(['status'=>0,'is_temp'=>0])->get() as $key => $item){
            if($key > 1) continue;
            $this->data = $item->no_credit_note."<br />";
            // find data UW
            $uw = \App\Models\SyariahUnderwriting::where('no_debit_note',$item->no_debit_note)->first();   
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
                $bank = new \App\Models\BankAccount();
                $bank->owner = $item->tujuan_pembayaran;
                $bank->bank = $item->bank;
                $bank->no_rekening = $item->no_rekening;
                $bank->save();
            }

            if($uw){
                $in = \App\Models\Income::where('transaction_table','syariah_underwriting')->where('transaction_id',$uw->id)->first();
                if($in and $in->status==1){  
                    //jika statusnya belum paid maka embed cancelation ke form income premium receivable 
                    //dan mengurangi nominal dari premi yang diterima
                    $cancel = new \App\Models\IncomeCancel();
                    $cancel->income_id = $in->id;
                    $cancel->nominal = $item->refund;
                    $cancel->transaction_id = $item->id;
                    $cancel->transaction_table= "syariah_cancel";
                    $cancel->save();
                }else{
                    $expense = new \App\Models\Expenses();
                    $expense->user_id = \Auth::user()->id;
                    $expense->no_voucher = generate_no_voucher_income();
                    $expense->reference_no = $item->no_credit_note;
                    $expense->reference_date = $item->tgl_cancel;
                    $expense->nominal = abs($item->refund);
                    $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
                    $expense->reference_type = 'Cancelation';
                    $expense->transaction_id = $item->id;
                    $expense->transaction_table = 'syariah_cancel';
                    $expense->description = $item->ket;
                    $expense->rekening_bank_id = $bank->id;
                    $expense->type = 1;
                    $expense->save();
                }
            }

            $this->data = '<strong>Cancelation </strong> : '.format_idr($item->refund);
                
            $this->data .=$item->no_credit_note.'<br />'.$item->no_polis.' / '.$item->pemegang_polis;
        }
        if(\App\Models\SyariahCancel::where(['status'=>0,'is_temp'=>0])->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong>');   
            return redirect()->route('syariah.underwriting');
        }
    }
}
