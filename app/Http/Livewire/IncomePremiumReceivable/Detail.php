<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;
use App\Models\Income;
use App\Models\IncomeTitipanPremi;
use App\Models\KonvenMemo;
use App\Models\SyariahCancel;
use App\Models\Journal;
use App\Models\JournalPenyesuaian;
use App\Models\BankAccount;
use App\Models\BankAccountBalance;

class Detail extends Component
{
    public $data,$no_voucher,$client,$recipient,$reference_type,$reference_no,$reference_date,$description,$outstanding_balance,$tax_id,$payment_amount=0,$bank_account_id,$from_bank_account_id;
    public $payment_date,$tax_amount,$total_payment_amount,$is_readonly=false,$due_date;
    public $bank_charges,$showDetail='underwriting',$cancelation;
    public $titipan_premi,$temp_titipan_premi=[],$temp_arr_titipan_id=[],$total_titipan_premi=0;
    public $is_otp_editable=false,$otp,$is_submit;
    protected $listeners = ['emit-add-bank'=>'emitAddBank','set-titipan-premi'=>'setTitipanPremi','refresh-page'=>'$refresh','otp-editable'=>'otpEditable'];
    public function render()
    {
        return view('livewire.income-premium-receivable.detail');
    }

    public function otpEditable($otp)
    {
        $this->otp = $otp;
        $this->is_readonly = false;
        $this->is_otp_editable = true;
    }
    
    public function clearTitipanPremi()
    {
        $this->reset('temp_titipan_premi','temp_arr_titipan_id','total_titipan_premi','from_bank_account_id','bank_account_id');
        $this->emit('init-form');
    }

    public function updated($propertyName)
    {
        $this->outstanding_balance = abs(replace_idr($this->payment_amount) - $this->data->nominal);
        $this->emit('init-form');
    }

    public function setTitipanPremi($id)
    {
        $this->temp_arr_titipan_id[] = $id;
        $this->temp_titipan_premi = \App\Models\Income::whereIn('id',$this->temp_arr_titipan_id)->get();
        $this->total_titipan_premi = 0;
        foreach($this->temp_titipan_premi as $titipan){
            $this->total_titipan_premi += $titipan->outstanding_balance;
        }
        if($this->total_titipan_premi < $this->data->nominal){
            $this->payment_amount = $this->total_titipan_premi;
            $this->outstanding_balance = abs(replace_idr($this->payment_amount) - $this->data->nominal);
        }elseif($this->total_titipan_premi>$this->data->nominal){
            $this->payment_amount = $this->data->nominal;
            $this->outstanding_balance = 0;
        }
        $this->emit('init-form');
    }

    public function emitAddBank($id)
    {
        $this->to_bank_account_id = $id;
        $this->emit('init-form');
    }
    
    public function mount($id)
    {
        \LogActivity::add("Income - Premium Receivable Edit {$id}");
        $this->data = Income::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->payment_date = $this->data->payment_date?$this->data->payment_date : date('Y-m-d');
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->from_bank_account_id = $this->data->from_bank_account_id;
        $this->payment_amount = $this->data->payment_amount;
        $this->outstanding_balance = $this->data->outstanding_balance;
        $this->description = $this->data->description;
        $this->due_date = $this->data->due_date;
        $this->bank_charges = $this->bank_charges;
        // cek titipan premi
        $this->titipan_premi = IncomeTitipanPremi::where(['income_premium_id'=>$this->data->id,'transaction_type'=>'Premium Receive'])->get();      
        if($this->data->status==1) $this->description = 'Premi ab '. (isset($this->data->uw->pemegang_polis) ? ($this->data->uw->pemegang_polis .' bulan '. $this->data->uw->bulan .' dengan No Invoice :'.$this->data->uw->no_kwitansi_debit_note) : ''); 
        if($this->payment_amount =="") $this->payment_amount=$this->data->nominal;
        if($this->data->status==2 || $this->data->status==4){ $this->is_readonly = true;}
        if($this->data->type==1){ // Konven
            foreach($this->data->cancelation_konven as $cancel) $this->payment_amount -= $cancel->nominal;
            foreach($this->data->endorsement_konven as $endors) $this->payment_amount -= $endors->nominal;
        }
        if($this->data->type==2){ // Syariah
            foreach($this->data->cancelation_syariah as $cancel) $this->payment_amount -= $cancel->nominal;
            foreach($this->data->endorsement_syariah as $endors) $this->payment_amount -= $endors->nominal;
        }    
        $this->payment_amount = format_idr($this->payment_amount);
    }
    
    public function showDetailCancelation($id)
    {
        if($this->data->type==1) $this->cancelation = KonvenMemo::find($id);
        if($this->data->type==2) $this->cancelation = SyariahCancel::find($id);
        $this->showDetail='cancelation';
        $this->emit('init-form');
    }

    public function save()
    {   
        $this->emit('init-form');
        $validate = ['payment_amount'=>'required'];
        $validate_message= [];

        if($this->is_otp_editable){
            $cek = \Otp::check($this->otp);
            if($cek['status']==false){
                $this->is_submit = $cek['status'];
                $validate['is_submit'] = 'boolean';
                $validate_message['is_submit.boolean'] = $cek['message'];
            }
        }
        
        if(!$this->temp_titipan_premi and $this->titipan_premi->count()==0) $validate['bank_account_id']='required';

        $this->validate($validate,$validate_message);
        $this->payment_amount = replace_idr($this->payment_amount);
        $this->bank_charges = replace_idr($this->bank_charges);
        if($this->payment_amount==$this->data->nominal || $this->payment_amount > $this->data->nominal) $this->data->status=2;//paid
        if($this->payment_amount<$this->data->nominal) $this->data->status=3;//outstanding
        $this->data->outstanding_balance = replace_idr($this->outstanding_balance);
        $this->data->payment_amount = $this->payment_amount;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->payment_date = $this->payment_date;
        $this->data->description = $this->description;
        $this->data->from_bank_account_id = $this->from_bank_account_id;
        $this->data->bank_charges = $this->bank_charges;
        $this->data->user_id = \Auth::user()->id;
        $this->data->save();
        
        if($this->temp_titipan_premi){
            $total_titipan_premi = 0;
            $nominal_titipan = 0;
            foreach($this->temp_arr_titipan_id as $k => $val){
                $item = Income::find($val);
                $total_titipan_premi += $item->outstanding_balance;            
                if($total_titipan_premi < $this->data->nominal){
                    IncomeTitipanPremi::create([
                        'income_premium_id' => $this->data->id,
                        'income_titipan_id' => $item->id,
                        'nominal' => $item->nominal,
                        'transaction_type'=>'Premium Receive'
                    ]);
                    $item->payment_amount = $item->nominal;
                    $item->outstanding_balance = $item->outstanding_balance - $item->nominal;
                    $item->status = 2;
                }elseif($total_titipan_premi > $this->data->nominal){ // jika sudah melebihi nominal premi, maka status titipan premi jadi completed
                    $total_titipan_premi -= $item->outstanding_balance;
                    IncomeTitipanPremi::create([
                        'income_premium_id' => $this->data->id,
                        'income_titipan_id' => $item->id,
                        'nominal' => ($this->data->nominal - $total_titipan_premi),
                        'transaction_type'=>'Premium Receive'
                    ]);
                    $item->outstanding_balance = $item->outstanding_balance - ($this->data->nominal - $total_titipan_premi);
                    $item->payment_amount = $item->payment_amount + ($this->data->nominal - $total_titipan_premi);
                }
                $item->save();
            }
        }

        if($this->data->status==2){
            $coa_premium_receivable = 0;
            if($this->data->type==1){
                $line_bussines = isset($this->data->uw->line_bussines) ? $this->data->uw->line_bussines : '';
                $no_kwitansi_debit_note = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
            }else{
                $no_kwitansi_debit_note = isset($this->data->uw_syariah->no_kwitansi_debit_note)?$this->data->uw_syariah->no_kwitansi_debit_note:'';
                $line_bussines = isset($this->data->uw_syariah->line_bussines) ? $this->data->uw_syariah->line_bussines : '';
            }
            // jika ada perubahan data dari yang sudah paid
            // maka ter-create journal penyesuaian me refer ke journal sebelumnya
            if($this->is_otp_editable){
                $find_journal = Journal::where(['transaction_table'=>'income','transaction_id'=>$this->data->id])->first();
                
                Journal::where(['transaction_table'=>'income','transaction_id'=>$this->data->id])->update(['is_adjusting'=>1,'debit'=>0,'kredit'=>0,'saldo'=>0]);

                \LogActivity::add("Income - Premium Receivable Editable OTP {$this->data->id}");
                $count_journal_penyesuaian = JournalPenyesuaian::count()+1;
                if(!$find_journal){
                    session()->flash('message-success',__('Data saved successfully'));
                    return redirect()->route('income.premium-receivable');
                }
            }

            // set balance
            $bank_balance = BankAccount::find($this->data->rekening_bank_id);
            if($bank_balance and !$this->is_otp_editable){
                $bank_balance->open_balance = $bank_balance->open_balance + $this->payment_amount;
                $bank_balance->save();

                $balance = new BankAccountBalance();
                $balance->kredit = $this->payment_amount;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 4; // Inhouse transfer
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }

            if(isset($line_bussines)){
                switch($line_bussines){
                    case "JANGKAWARSA":
                        $coa_premium_receivable = 58; //Premium Receivable Jangkawarsa
                    break;
                    case "EKAWARSA":
                        $coa_premium_receivable = 59; //Premium Receivable Ekawarsa
                    break;
                    case "DWIGUNA":
                        $coa_premium_receivable = 60; //Premium Receivable Dwiguna
                    break;
                    case "DWIGUNA KOMBINASI":
                        $coa_premium_receivable = 61; //Premium Receivable Dwiguna Kombinasi
                    break;
                    case "KECELAKAAN":
                        $coa_premium_receivable = 62; //Premium Receivable Kecelakaan Diri
                    break;
                    default: 
                        $coa_premium_receivable = 63; //Premium Receivable Other Tradisional
                    break;
                }
                
                // Premium Receivable
                if($this->is_otp_editable){
                    // journal penyesuaian
                    $journal = new JournalPenyesuaian();
                    $journal->kredit = 0;
                    $journal->debit = 0;
                    $journal->saldo = 0; 
                    $journal->journal_no_voucher = $find_journal->no_voucher;
                    $journal->no_voucher = generate_no_voucher($coa_premium_receivable, $count_journal_penyesuaian);
                } else {
                    $journal = new Journal();
                    $journal->kredit = $this->payment_amount;
                    $journal->debit = 0;
                    $journal->saldo = $this->payment_amount; 
                    $journal->no_voucher = generate_no_voucher($coa_premium_receivable,$this->data->id);
                }

                $journal->coa_id = $coa_premium_receivable;
                $journal->date_journal = $this->payment_date;
                $journal->description = $this->description;
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'income';
                $journal->transaction_number = $no_kwitansi_debit_note;
                $journal->save();

                if($this->payment_amount < $this->data->nominal){
                    if($this->is_otp_editable){
                        // journal penyesuaian
                        $journal = new JournalPenyesuaian();
                        $journal->kredit = 0;
                        $journal->debit = 0;
                        $journal->saldo = 0; 
                        $journal->journal_no_voucher = $find_journal->no_voucher;
                        $journal->no_voucher = generate_no_voucher(206, $count_journal_penyesuaian);
                    } else {
                        $journal = new Journal();
                        $journal->kredit = $this->payment_amount - $this->data->nominal;
                        $journal->debit = 0;
                        $journal->saldo = $this->payment_amount - $this->data->nominal;
                        $journal->no_voucher = generate_no_voucher(206,$this->data->id);
                    }

                    $journal->coa_id = 206;//Other Payable
                    $journal->date_journal = $this->payment_date;
                    $journal->description = $this->description;
                    $journal->transaction_id = $this->data->id;
                    $journal->transaction_table = 'income';
                    $journal->transaction_number = $no_kwitansi_debit_note;
                    $journal->save();
                }
                // Bank Charges
                if(!empty($this->bank_charges)){
                    if($this->is_otp_editable){
                        // journal penyesuaian
                        $journal = new JournalPenyesuaian();
                        $journal->kredit = 0;
                        $journal->debit = 0;
                        $journal->saldo = 0; 
                        $journal->journal_no_voucher = $find_journal->no_voucher;
                        $journal->no_voucher = generate_no_voucher(347, $count_journal_penyesuaian);
                    } else {
                        $journal = new Journal();
                        $journal->kredit = replace_idr($this->bank_charges);
                        $journal->debit = 0;
                        $journal->saldo = replace_idr($this->bank_charges);
                        $journal->no_voucher = generate_no_voucher(347,$this->data->id);
                    }

                    $journal->coa_id = 347; // Bank Charges
                    $journal->date_journal = $this->payment_date;
                    $journal->description = $this->description;
                    $journal->transaction_id = $this->data->id;
                    $journal->transaction_table = 'income';
                    $journal->transaction_number = $no_kwitansi_debit_note;
                    $journal->save();
                }

                if($this->temp_titipan_premi || $this->titipan_premi->count()>0){
                    # jika premium receive dari titipan premi maka ter create coa premium suspend
                    if($this->is_otp_editable){
                        // journal penyesuaian
                        $journal = new JournalPenyesuaian();
                        $journal->kredit = 0;
                        $journal->debit = 0;
                        $journal->saldo = 0; 
                        $journal->journal_no_voucher = $find_journal->no_voucher;
                        $journal->no_voucher = generate_no_voucher(get_coa(406000), $count_journal_penyesuaian);
                    } else {
                        $journal = new Journal();
                        $journal->debit = $this->bank_charges + $this->payment_amount;
                        $journal->kredit = 0;
                        $journal->saldo = $this->bank_charges + $this->payment_amount;
                        $journal->no_voucher = generate_no_voucher(get_coa(406000),$this->data->id);
                    }

                    $journal->coa_id = get_coa(406000); // premium suspend
                    $journal->date_journal = $this->payment_date;
                    $journal->description = $this->description;
                    $journal->transaction_id = $this->data->id;
                    $journal->transaction_table = 'income';
                    $journal->transaction_number = $no_kwitansi_debit_note;
                    $journal->save();
                }
                else
                {
                    # jika penerimaan premi dari transfer maka tercreate coa berdasarkan banknya
                    $coa_bank_account = BankAccount::find($this->bank_account_id);
                    if($coa_bank_account and !empty($coa_bank_account->coa_id)){
                        if($this->is_otp_editable){
                            // journal penyesuaian
                            $journal = new JournalPenyesuaian();
                            $journal->kredit = 0;
                            $journal->debit = 0;
                            $journal->saldo = 0; 
                            $journal->journal_no_voucher = $find_journal->no_voucher;
                            $journal->no_voucher = generate_no_voucher($coa_bank_account->coa_id, $count_journal_penyesuaian);
                        } else {
                            $journal = new Journal();
                            $journal->debit = $this->bank_charges + $this->payment_amount;
                            $journal->kredit = 0;
                            $journal->saldo = $this->bank_charges + $this->payment_amount;
                            $journal->no_voucher = generate_no_voucher($coa_bank_account->coa_id,$this->data->id);
                        }
                        
                        $journal->coa_id = $coa_bank_account->coa_id;
                        $journal->date_journal = $this->payment_date;
                        $journal->description = $this->description;
                        $journal->transaction_id = $this->data->id;
                        $journal->transaction_table = 'income';
                        $journal->transaction_number = $no_kwitansi_debit_note;
                        $journal->save();
                    }
                }
            }
        }

        \LogActivity::add("Income - Premium Receivable Save {$this->data->id}");

        session()->flash('message-success',__('Data saved successfully'));
        
        if(session()->get('url_back')){
            return redirect(session()->get('url_back'));
        }else{
            return redirect()->route('income.premium-receivable');
        }
    }
}