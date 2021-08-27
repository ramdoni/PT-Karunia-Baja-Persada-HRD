<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\GeneralLedger;
use App\Models\GeneralLedgerHistory;
use App\Models\Journal;
use App\Models\UserOtp;

class RevisiPreview extends Component
{
    public $data,$coa_group,$message_error,$is_valid=false,$otp_number;
    protected $listeners = ['preview-gl'=>'$refresh','otp-editable'=>'otpRequest'];

    public function render()
    {
        return view('livewire.general-ledger.revisi-preview');
    }

    public function mount(GeneralLedger $gl)
    {
        $this->data = $gl;
        $this->coa_group = $this->data->coa_group;
    }

    public function otpRequest($otp_number)
    {
        $this->otp_number = $otp_number;
        $this->is_valid = true;
    }

    public function submit()
    {
        $cek_otp = UserOtp::where(['otp'=>$this->otp_number,'status'=>0])->first();
        if($cek_otp){
            if(strtotime(date('Y-m-d H:i:s')) >= strtotime($cek_otp->expired)){
                $cek_otp->status = 1;
                $cek_otp->save();
                $this->message_error = 'OTP anda sudah expired, silahkan request OTP lagi.';
                $this->is_valid = false;
            }else{
                $revisi = $this->data->revisi + 1;
                $this->data->revisi = $revisi;
                $this->data->save();
                
                foreach(Journal::select("journals.*")->join('coas','coas.id','=','journals.coa_id')->where(['coas.coa_group_id'=>$this->coa_group->id])->where(function($table){ $table->where('status_general_ledger',1)->orWhere('general_ledger_id',$this->data->id); })->get() as $journal){
                    $journal = Journal::find($journal->id);
                    $journal->general_ledger_id = $this->data->id;
                    $journal->status_general_ledger = 2;
                    $journal->save();
                    // insert history
                    $history = new GeneralLedgerHistory();
                    $history->general_ledger_id = $this->data->id;
                    $history->journal_id = $journal->id;
                    $history->is_revisi = $revisi;
                    $history->save();
                }

                session()->flash('message-success','Revisi General Ledger '.$this->data->general_ledger_number.' Saved .');   
                    
                return redirect()->route('general-ledger.detail',$this->data->id);
            }
        }else{
            $this->message_error = 'OTP anda sudah tidak ditemukan, silahkan request OTP lagi.';
        }
    }
}
