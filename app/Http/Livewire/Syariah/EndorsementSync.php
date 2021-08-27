<?php

namespace App\Http\Livewire\Syariah;

use Livewire\Component;

class EndorsementSync extends Component
{
    public $total_sync,$is_sync,$total_finish=0,$data,$total_success=0,$total_failed=0;
    protected $listeners = ['is_sync_endorsement'=>'sync'];
    public function render()
    {
        return view('livewire.syariah.endorsement-sync');
    }
    public function mount()
    {
        $this->total_sync = \App\Models\SyariahEndorsement::where(['status'=>0,'is_temp'=>0])->count();
    }
    public function cancel_sync(){
        $this->is_sync=false;
        $this->emit('refresh-page');
    }
    public function sync()
    {
        if($this->is_sync==false) return false;
        $this->emit('is_sync_endorsement');
        foreach(\App\Models\SyariahEndorsement::where(['status'=>0,'is_temp'=>0])->get() as $key => $item){
            if($key > 1) continue;
            $this->data = $item->no_dn_cn."<br />";
            // find data UW
            $uw = \App\Models\SyariahUnderwriting::where('no_debit_note',$item->no_dn_awal)->first();   
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
            $this->total_finish++;
            if(!$uw) continue; // Skip jika tidak ditemukan data UW
            $bank = \App\Models\BankAccount::where('no_rekening',replace_idr($item->no_rekening))->first();
            if(!$bank){
                $bank = new \App\Models\BankAccount();
                $bank->no_rekening = $item->no_rekening;
                $bank->bank = $item->bank;
                $bank->owner = $item->tujuan_pembayaran;
                $bank->save();
            }

            $income = \App\Models\Income::where(['transaction_table'=>'syariah_underwriting','transaction_id'=>$uw->id,'status'=>1])->first();
            if($income){
                $endors = new \App\Models\IncomeEndorsement();
                $endors->income_id = $income->id;
                $endors->nominal =  abs($item->dengan_tagihan_atau_refund_premi);
                $endors->transaction_table = 'syariah_endorsement';
                $endors->transaction_id = $item->id;
                $endors->type = $item->dengan_tagihan_atau_refund_premi >0 ? 2 : 1;
                $endors->save();
            }else{
                if($item->dengan_tagihan_atau_refund_premi >0){ //jika plus maka menjadi Debit Note
                    $income = new \App\Models\Income();
                    $income->user_id = \Auth::user()->id;
                    $income->no_voucher = generate_no_voucher_income();
                    $income->reference_no = $item->no_dn_cn;
                    $income->reference_date = $item->tgl_endors;
                    $income->nominal = abs($item->dengan_tagihan_atau_refund_premi);
                    $income->client = $item->no_polis.' / '.$item->pemegang_polis;
                    $income->reference_type = 'Endorsement DN';
                    $income->transaction_id = $item->id;
                    $income->transaction_table = 'syariah_endorsement';
                    $income->description = $item->jenis_perubahan;
                    $income->rekening_bank_id = $bank->id;
                    $income->type = 1;
                    $income->save();
                }else{
                    $expense = new \App\Models\Expenses();
                    $expense->user_id = \Auth::user()->id;
                    $expense->no_voucher = generate_no_voucher_income();
                    $expense->reference_no = $item->no_dn_cn;
                    $expense->reference_date = $item->tgl_endors;
                    $expense->nominal = abs($item->dengan_tagihan_atau_refund_premi);
                    $expense->recipient = $item->no_polis.' / '.$item->pemegang_polis;
                    $expense->reference_type = 'Endorsement CN';
                    $expense->transaction_id = $item->id;
                    $expense->transaction_table = 'syariah_endorsement';
                    $expense->description = $item->jenis_perubahan;
                    $expense->rekening_bank_id = $bank->id;
                    $expense->type = 1;
                    $expense->save();
                }
            }            
            $this->data = '<strong>Endorsement </strong> : '.format_idr($item->refund);   
            $this->data .=$item->no_dn_cn.'<br />'.$item->no_polis.' / '.$item->pemegang_polis;
        }
        if(\App\Models\SyariahEndorsement::where(['status'=>0, 'is_temp'=>0])->count()==0){
            session()->flash('message-success','Synchronize success, Total Success <strong>'.$this->total_success.'</strong>, Total Failed <strong>'.$this->total_failed.'</strong>');   
            return redirect()->route('syariah.underwriting');
        }
    }
}
