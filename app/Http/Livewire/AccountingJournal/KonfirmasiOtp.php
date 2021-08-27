<?php

namespace App\Http\Livewire\AccountingJournal;

use Livewire\Component;
use App\Models\UserOtp;
use App\Models\UserOtpHistory;

class KonfirmasiOtp extends Component
{
    public $otp,$is_request_otp=false,$message,$danger;
    public function render()
    {
        return view('livewire.accounting-journal.konfirmasi-otp');
    }

    public function request_otp()
    {
        $this->reset('message','danger');

        $this->is_request_otp=true;
        $phone = get_supervisor();

        \LogActivity::add("Accounting - Journal Request OTP");

        $cek_otp = UserOtp::where('request_user_id',\Auth::user()->id)->whereDate('expired',date('Y-m-d'))->count();
        if($cek_otp >=3){
            $this->danger = 'Gagal, anda telah melakukan permintaan OTP sebelumnya';
            return false;
        }
        if($phone){
            $otp = genereate_otp();
            send_wa(['phone'=>$phone,'message'=>"Pengajuan perubahan data dari sistem\n\nOTP: *{$otp['otp']}*\nExpired : {$otp['expired']}"]);

            $this->message = 'OTP berhasil di kirim silahkan.';

        }else $this->danger = 'Supervisor tidak ditemukan, silahkan atur Supervisor di login administrator';
    }

    public function submit()
    {
        $this->reset('message','danger');
        \LogActivity::add("Accounting Validate OTP");

        $this->validate([
            'otp' => 'required'
        ]);
        // check request 
        $check_history = UserOtpHistory::where(['user_otp_id'=>\Auth::user()->id,'status'=>1])->count();
        if($check_history >=10){
            $this->danger = 'OTP anda salah, silahkan di coba kembali.';
        }else{
            $cek_otp = UserOtp::where(['otp'=>$this->otp,'status'=>0])->first();
            if($cek_otp){
                if(strtotime(date('Y-m-d H:i:s')) >= strtotime($cek_otp->expired)){
                    $cek_otp->status = 1;
                    $cek_otp->save();
                    $this->danger = 'OTP anda sudah expired.';
                }else{
                    $this->emit('otp-editable',$this->otp);
                }
            }else{
                $history = new UserOtpHistory();
                $history->user_otp_id = \Auth::user()->id;
                $history->status = 1;
                $history->transaction_table = 'Accounting - Journal';
                $history->save();
                $this->danger = 'OTP anda salah, silahkan di coba kembali.';
            }
        }
    }
}
