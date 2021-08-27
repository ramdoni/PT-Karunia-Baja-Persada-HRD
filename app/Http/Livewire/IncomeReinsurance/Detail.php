<?php

namespace App\Http\Livewire\IncomeReinsurance;

use Livewire\Component;
use App\Models\Income;
use App\Models\IncomeTitipanPremi;

class Detail extends Component
{
    public $data,$no_voucher,$client,$recipient,$reference_type,$reference_no,$reference_date,$description,$outstanding_balance,$tax_id,$payment_amount=0,$bank_account_id;
    
    public $payment_date,$tax_amount,$total_payment_amount,$is_readonly=false,$is_finish=false;
    
    public $bank_charges,$from_bank_account_id;
    
    public $titipan_premi,$temp_titipan_premi=[],$temp_arr_titipan_id=[],$total_titipan_premi=0;
    
    protected $listeners = ['emit-add-bank'=>'emitAddBank','set-titipan-premi'=>'setTitipanPremi','refresh-page'=>'$refresh'];

    public function render()
    {
        return view('livewire.income-reinsurance.detail');
    }

    public function mount($id)
    {
        $this->data = Income::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->payment_date = $this->data->payment_date?$this->data->payment_date : date('Y-m-d');
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->from_bank_account_id = $this->data->from_bank_account_id;
        $this->payment_amount = format_idr($this->data->payment_amount);
        $this->total_payment_amount = $this->data->total_payment_amount;
        $this->outstanding_balance = $this->data->outstanding_balance;
        $this->titipan_premi = IncomeTitipanPremi::where(['income_premium_id'=>$this->data->id,'transaction_type'=>'Reinsurance Commision'])->get();      
        
        if($this->data->status==1) $this->description = 'Pembayaran Komisi ab '. (isset($this->data->uw->pemegang_polis) ? $this->data->uw->pemegang_polis : ''); 
        

        if($this->payment_amount =="") $this->payment_amount=format_idr($this->data->nominal);
        if($this->data->status==2) $this->is_readonly = true;
     
        \LogActivity::add("Income - Reinsurance Commision Detail {$this->data->id}");
    }

    public function updated($propertyName)
    {
        if($propertyName=='payment_amount') $this->outstanding_balance = $this->data->nominal - replace_idr($this->payment_amount);
        
        $this->emit('init-form');
    }

    public function clearTitipanPremi()
    {
        $this->reset('temp_titipan_premi','temp_arr_titipan_id','total_titipan_premi','from_bank_account_id','bank_account_id');
        $this->emit('init-form');
    }

    public function emitAddBank($id)
    {
        $this->from_bank_account_id = $id;
        $this->emit('init-form');
    }
    
    public function setTitipanPremi($id)
    {
        $this->temp_arr_titipan_id[] = $id;
        $this->temp_titipan_premi = Income::whereIn('id',$this->temp_arr_titipan_id)->get();
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

    public function save()
    {
        $validate = ['payment_amount'=>'required'];

        if(!$this->temp_titipan_premi and !$this->titipan_premi) {
            $validata['from_bank_account_id'] = 'required'; 
            $validate['bank_account_id']='required';
        }
        
        $this->validate($validate);

        $this->payment_amount = replace_idr($this->payment_amount);
        if($this->payment_amount==$this->data->nominal || $this->payment_amount > $this->data->nominal) $this->data->status=2;//paid
        if($this->payment_amount<$this->data->nominal) $this->data->status=3;//outstanding
        
        $this->data->outstanding_balance = replace_idr($this->outstanding_balance);
        $this->data->payment_amount = $this->payment_amount;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->from_bank_account_id = $this->from_bank_account_id;
        $this->data->payment_date = $this->payment_date;
        $this->data->description = $this->description;
        $this->data->save();

        if($this->temp_titipan_premi){
            $total_titipan_premi = 0;
            foreach($this->temp_arr_titipan_id as $val){
                $item = Income::find($val);
                $total_titipan_premi += $item->outstanding_balance;            
                if($total_titipan_premi < $this->data->nominal){
                    IncomeTitipanPremi::create([
                        'income_premium_id' => $this->data->id,
                        'income_titipan_id' => $item->id,
                        'nominal' => $item->nominal,
                        'transaction_type'=>'Reinsurance Commision'
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
                        'transaction_type'=>'Reinsurance Commision'
                    ]);
                    $item->outstanding_balance = $item->outstanding_balance - ($this->data->nominal - $total_titipan_premi);
                    $item->payment_amount = $item->payment_amount + ($this->data->nominal - $total_titipan_premi);
                }
                $item->save();
            }
        }

        if($this->data->status==2){
            $coa_reinsurance_commision = 0;
            switch($this->data->uw->line_bussines){
                case "JANGKAWARSA":
                    $coa_reinsurance_commision = 244; //Reinsurance Commission Fee Jangkawarsa
                break;
                case "EKAWARSA":
                    $coa_reinsurance_commision = 245; //Reinsurance Commission Fee Ekawarsa
                break;
                case "DWIGUNA":
                    $coa_reinsurance_commision = 246; //Reinsurance Commission Fee Dwiguna
                break;
                case "DWIGUNA KOMBINASI":
                    $coa_reinsurance_commision = 247; //Reinsurance Commission Fee Dwiguna Kombinasi
                break;
                case "KECELAKAAN":
                    $coa_reinsurance_commision = 248; //Reinsurance Commission Fee Kecelakaan Diri
                break;
                default: 
                    $coa_reinsurance_commision = 249; //Reinsurance Commission Fee Other Tradisional
                break;
            }        
            // Premium Receivable
            $journal = new \App\Models\Journal();
            $journal->coa_id = $coa_reinsurance_commision;
            $journal->no_voucher = generate_no_voucher($coa_reinsurance_commision,$this->data->id);
            $journal->date_journal = date('Y-m-d');
            $journal->kredit = $this->payment_amount;
            $journal->debit = 0;
            $journal->saldo = $this->payment_amount;
            $journal->description = $this->description;
            $journal->transaction_id = $this->data->id;
            $journal->transaction_table = 'expenses';
            $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
            $journal->save();

            if($this->payment_amount < $this->data->nominal){
                $journal = new \App\Models\Journal();
                $journal->coa_id = 206;//Other Payable
                $journal->no_voucher = generate_no_voucher(206,$this->data->id);
                $journal->date_journal = date('Y-m-d');
                $journal->kredit = $this->payment_amount - $this->data->nominal;
                $journal->debit = 0;
                $journal->saldo = $this->payment_amount - $this->data->nominal;
                $journal->description = $this->description;
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
            // Bank Charges
            if(!empty($this->bank_charges)){
                $journal = new \App\Models\Journal();
                $journal->coa_id = 347; // Bank Charges
                $journal->no_voucher = generate_no_voucher(347,$this->data->id);
                $journal->date_journal = date('Y-m-d');
                $journal->kredit = replace_idr($this->bank_charges);
                $journal->debit = 0;
                $journal->saldo = replace_idr($this->bank_charges);
                $journal->description = $this->description;
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
            if($this->temp_titipan_premi){
                # jika premium receive dari titipan premi maka ter create coa premium suspend
                $journal = new \App\Models\Journal();
                $journal->coa_id = get_coa(406000); // premium suspend
                $journal->no_voucher = generate_no_voucher(get_coa(406000),$this->data->id);
                $journal->date_journal = date('Y-m-d');
                $journal->debit = $this->bank_charges + $this->payment_amount;
                $journal->kredit = 0;
                $journal->saldo = $this->bank_charges + $this->payment_amount;
                $journal->description = $this->description;
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'income';
                $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
            else
            {
                // COA Bank
                $coa_bank_account = \App\Models\BankAccount::find($this->bank_account_id);
                $journal = new \App\Models\Journal();
                $journal->coa_id = $coa_bank_account->coa_id;
                $journal->no_voucher = generate_no_voucher($coa_bank_account->coa_id,$this->data->id);
                $journal->date_journal = date('Y-m-d');
                $journal->debit = $this->bank_charges + $this->payment_amount;
                $journal->kredit = 0;
                $journal->saldo = $this->bank_charges + $this->payment_amount;
                $journal->description = $this->description;
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($this->data->uw->no_kwitansi_debit_note)?$this->data->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
        }

        \LogActivity::add("Income - Reinsurance Commision Submit {$this->data->id}");

        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('income.reinsurance');
    }
}