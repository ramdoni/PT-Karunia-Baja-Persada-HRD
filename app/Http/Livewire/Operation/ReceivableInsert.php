<?php

namespace App\Http\Livewire\Operation;

use Livewire\Component;

class ReceivableInsert extends Component
{
    public $data;
    public $account_id,$count_account,$bank_account_id,$receive_amount=0,$date,$outstanding_balance;
    public $kredit,$debit,$description_coa,$coa_id,$no_voucher,$payment_date;
    public $total_kredit,$total_debit;
    public $client,$reference_no,$reference_type,$reference_date,$description;
    public function render()
    {
        return view('livewire.operation.receivable-insert');
    }

    public function mount()
    {
        $this->count_account[] = 1;
        $this->coa_id[] = "";
        $this->description_coa[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;
        $this->payment_date[] = '';
    }

    
    public function addAccountForm()
    {
        $this->count_account[] = count($this->count_account);
        $this->coa_id[] = "";
        $this->description_coa[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;
        $this->payment_date[] = '';
        
        $this->emit('listenAddAccountForm');
    }

    public function deleteAccountForm($key)
    {
        unset($this->count_account[$key]);
        unset($this->coa_id[$key]);
        unset($this->description_coa[$key]);
        unset($this->kredit[$key]);
        unset($this->debit[$key]);
        unset($this->payment_date[$key]);

        $this->sumDebit();
        $this->sumKredit();
    }
    
    public function setNoVoucher($key)
    {
        if($this->no_voucher=="") $this->no_voucher = generate_no_voucher($this->coa_id[$key]);
    }
    
    public function sumDebit()
    {
        if(!$this->debit) return false;

        $this->total_debit=0;
        foreach($this->debit as $i){
            $this->total_debit += replace_idr($i);
        }
    }

    public function sumKredit() 
    {
        $this->total_kredit=0;
        foreach($this->kredit as $i){
            $this->total_kredit += replace_idr($i);
        }
    }

    public function saveAsDraft()
    {
        // $this->validate([
        //     'reference_no' => 'required',
        //     'reference_date' => 'required',
        //     'reference_type' => 'required',
        //     'no_voucher' => 'required'
        // ]);

        $this->save();
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('operation');
    }
    
    public function save()
    {
        $data = new \App\Models\Income();
        $data->no_voucher = $this->no_voucher;
        $data->client = $this->client;
        $data->reference_no = $this->reference_no;
        $data->reference_type = $this->reference_type;
        $data->description = $this->description;
        $data->reference_date = $this->reference_date;
        $data->user_id = \Auth::user()->id;
        $data->status = 1;
        $data->rekening_bank_id = $this->bank_account_id;
        $data->total_debit = $this->total_debit;
        $data->total_kredit = $this->total_kredit;
        $data->nominal = $this->total_kredit;
        $data->save();
        $this->data = $data;
        foreach($this->coa_id as $key => $val){
            if($val=="") continue;
            $coa  = new \App\Models\IncomeCoa();
            $coa->income_id = $data->id;
            $coa->coa_id = $val;
            $coa->payment_date = date('Y-m-d',strtotime($this->payment_date[$key]));
            $coa->debit = replace_idr(isset($this->debit[$key])?$this->debit[$key]:0);
            $coa->kredit = replace_idr(isset($this->kredit[$key])?$this->kredit[$key]:0);
            $coa->description = isset($this->description_coa[$key])?$this->description_coa[$key]:'';
            $coa->save();
        }
    }

    public function saveToJournal()
    {
        $this->validate([
            'bank_account_id'=>'required',
            'reference_no' => 'required',
            'reference_date' => 'required',
            'reference_type' => 'required',
            'total_kredit'=>'same:total_debit'
        ]);
        
        $this->save();
        $this->data->status = 2;
        $this->data->save();
        
        // save to journal
        foreach($this->coa_id as $key => $val){
            if($val=="") continue;
            $data  = new \App\Models\Journal();
            $data->transaction_id = $this->data->id;
            $data->transaction_table = 'income'; 
            $data->coa_id = $val;
            $data->no_voucher = $this->no_voucher;
            $data->date_journal = date('Y-m-d');//date('Y-m-d',strtotime($this->payment_date[$key]));
            $data->debit = replace_idr(isset($this->debit[$key])?$this->debit[$key]:0);
            $data->kredit = replace_idr(isset($this->kredit[$key])?$this->kredit[$key]:0);
            $data->description = isset($this->description_coa[$key])?$this->description_coa[$key]:'';
            $data->bank_account_id = $this->bank_account_id;
            $data->saldo = replace_idr(isset($this->debit[$key]) ? $this->debit[$key] : (isset($this->kredit[$key])?$this->kredit[$key] : 0));
            $data->save();
        }

        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('operation');
    }
}
