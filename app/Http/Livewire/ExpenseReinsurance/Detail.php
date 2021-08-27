<?php

namespace App\Http\Livewire\ExpenseReinsurance;

use Livewire\Component;

class Detail extends Component
{
    public $data,$no_voucher,$client,$recipient,$reference_type,$reference_no,$reference_date,$description,$outstanding_balance,$tax_id,$payment_amount=0,$bank_account_id;
    public $payment_date,$tax_amount,$total_payment_amount,$is_readonly=false,$is_finish=false;
    public $bank_charges,$from_bank_account_id;
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    protected $rules = [
        'bank_account_id'=>'required',
        'payment_amount'=>'required',
        'from_bank_account_id' => 'required'
    ];
    public function render()
    {
        return view('livewire.expense-reinsurance.detail');
    }
    public function emitAddBank($id)
    {
        $this->bank_account_id = $id;
    }
    public function updated($propertyName)
    {
        if($propertyName=='payment_amount'){
            $this->outstanding_balance = $this->data->nominal  - replace_idr($this->payment_amount);
        }
        $this->emit('init-form');
    }
    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->payment_date = $this->data->payment_date?$this->data->payment_date : date('Y-m-d');
        $this->bank_account_id = $this->data->rekening_bank_id;
        $this->from_bank_account_id = $this->data->from_bank_account_id;
        $this->payment_amount = format_idr($this->data->payment_amount);
        $this->total_payment_amount = $this->data->total_payment_amount;

        if($this->payment_amount =="") $this->payment_amount=$this->data->nominal;
        if($this->data->status==2) $this->is_readonly = true;
        
        \LogActivity::add("Expense - Reinsurance Premium Detail {$this->data->id}");
    }
    public function save($type)
    {
        $this->validate();
        $this->payment_amount = replace_idr($this->payment_amount);
        if($this->payment_amount==$this->data->nominal) $this->data->status=2;//paid
        if($this->payment_amount!=$this->data->nominal) $this->data->status=3;//outstanding
        if($type=='Draft') $this->data->status =4; // Draft

        $this->data->outstanding_balance = replace_idr($this->outstanding_balance);
        $this->data->payment_amount = $this->payment_amount;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->payment_date = $this->payment_date;
        $this->data->from_bank_account_id = $this->from_bank_account_id;
        $this->data->save();
        if($this->data->status==2){
            // set balance
            $bank_balance = \App\Models\BankAccount::find($this->data->rekening_bank_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance + $this->payment_amount;
                $bank_balance->save();

                $balance = new \App\Models\BankAccountBalance();
                $balance->kredit = $this->payment_amount;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 5; // Reinsurance Premium
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }

            if($this->data->transaction_table=='konven_reinsurance'){
                $reas = \App\Models\KonvenReinsurance::find($this->data->transaction_id);
                $coa_reinsurance_premium_payable = 0;
                switch($reas->ekawarsa_jangkawarsa){
                    case "JANGKAWARSA":
                        $coa_reinsurance_premium_payable = 168;
                    break;
                    case "EKAWARSA":
                        $coa_reinsurance_premium_payable = 169;
                    break;
                    case "DWIGUNA":
                        $coa_reinsurance_premium_payable = 170;
                    break;
                    case "DWIGUNA KOMBINASI":
                        $coa_reinsurance_premium_payable = 171;
                    break;
                    case "KECELAKAAN":
                        $coa_reinsurance_premium_payable = 172;
                    break;
                    default: 
                        $coa_reinsurance_premium_payable = 173; // Others Tradisional
                    break;
                }    
            }
            $coa_bank_charges = 347;
            // Bank
            $coa_bank_account = \App\Models\BankAccount::find($this->bank_account_id);
            if($coa_bank_account->coa_id){
                $journal = new \App\Models\Journal();
                $journal->coa_id = $coa_bank_account->coa_id;
                $journal->no_voucher = generate_no_voucher($coa_bank_account->coa_id,$this->data->id);
                $journal->date_journal = date('Y-m-d');
                $journal->kredit = $this->bank_charges + $this->payment_amount;
                $journal->debit = 0;
                $journal->saldo = $this->bank_charges + $this->payment_amount;
                $journal->description = $this->description ? $this->description : 'Pembayaran Premi Reas '.$reas->broker_re.' ('.$reas->keterangan.')';
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($reas->uw->no_kwitansi_debit_note)?$reas->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
            // Bank Charges
            if(!empty($this->bank_charges)){
                $journal = new \App\Models\Journal();
                $journal->coa_id = $coa_bank_charges;
                $journal->no_voucher = generate_no_voucher($coa_bank_charges,$this->data->id);
                $journal->date_journal = date('Y-m-d');
                $journal->debit = replace_idr($this->bank_charges);
                $journal->kredit = 0;
                $journal->saldo = replace_idr($this->bank_charges);
                $journal->description = $this->description ? $this->description : 'Pembayaran Premi Reas '.$reas->broker_re.' ('.$reas->keterangan.')';
                $journal->transaction_id = $this->data->id;
                $journal->transaction_table = 'expenses';
                $journal->transaction_number = isset($reas->uw->no_kwitansi_debit_note)?$reas->uw->no_kwitansi_debit_note:'';
                $journal->save();
            }
            // Reinsurance Premium Payable
            $journal = new \App\Models\Journal();
            $journal->coa_id = $coa_reinsurance_premium_payable;
            $journal->no_voucher = generate_no_voucher($coa_reinsurance_premium_payable,$this->data->id);
            $journal->date_journal = date('Y-m-d');
            $journal->debit = $this->payment_amount;
            $journal->kredit = 0;
            $journal->saldo = $this->payment_amount;
            $journal->description = $this->description ? $this->description : 'Pembayaran Premi Reas '.$reas->broker_re.' ('.$reas->keterangan.')';
            $journal->transaction_id = $this->data->id;
            $journal->transaction_table = 'expenses';
            $journal->transaction_number = isset($reas->uw->no_kwitansi_debit_note)?$reas->uw->no_kwitansi_debit_note:'';
            $journal->save();
        }
        \LogActivity::add("Expense - Reinsurance Premium {$type} {$this->data->id}");

        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('expense.reinsurance-premium');
    }
    public function submit()
    {
        $this->save('Submit');
    }
    public function saveAsDraft()
    {
        $this->save('Draft');
    }
}