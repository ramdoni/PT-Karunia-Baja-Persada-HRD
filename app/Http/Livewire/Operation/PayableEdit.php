<?php

namespace App\Http\Livewire\Operation;

use Livewire\Component;

class PayableEdit extends Component
{
    public $data;
    public $account_id,$count_account,$bank_account_id,$receive_amount=0,$date,$outstanding_balance;
    public $expense_coa_id,$kredit,$debit,$description_coa,$coa_id,$no_voucher,$payment_date;
    public $total_kredit,$total_debit,$is_readonly=false;
    public $recipient,$reference_type,$reference_no,$reference_date,$description;
    public function render()
    {
        return view('livewire.operation.payable-edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->recipient = $this->data->recipient;
        $this->reference_type = $this->data->reference_type;
        $this->reference_no = $this->data->reference_no;
        $this->reference_date = $this->data->reference_date;
        $this->description = $this->data->description;
        $this->bank_account_id = $this->data->rekening_bank_id;
        foreach($this->data->coa as $k => $item){
            $this->expense_coa_id[$k] = $item->id;
            $this->count_account[$k] = $k;
            $this->coa_id[$k] = $item->coa_id;
            $this->kredit[$k] = format_idr($item->kredit);
            $this->debit[$k] = format_idr($item->debit);
            $this->description_coa[$k] = $item->description;
            $this->payment_date[$k] = $item->payment_date;
        }

        if(count($this->data->coa)==0){
            $this->count_account[] = 1;
            $this->coa_id[] = "";
            $this->description_coa[] = "";
            $this->kredit[] = 0;
            $this->debit[] = 0;
            $this->payment_date[] = '';
        }

        if($this->data->status==2) $this->is_readonly=true;
        $this->sumDebit();
        $this->sumKredit();
    }

    public function addAccountForm()
    {
        $this->expense_coa_id[] = '';
        $this->count_account[] = count($this->count_account);
        $this->coa_id[] = "";
        $this->description_coa[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;
        $this->payment_date[] = '';

        $this->emit('listenAddAccountForm');
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

    public function deleteAccountForm($key)
    {
        unset($this->count_account[$key]);
        unset($this->coa_id[$key]);
        unset($this->description_coa[$key]);
        unset($this->kredit[$key]);
        unset($this->debit[$key]);
        unset($this->payment_date[$key]);
        unset($this->expense_coa_id[$key]);
        $this->sumDebit();
        $this->sumKredit();
    }

    public function setNoVoucher($key)
    {
        if($this->no_voucher=="") $this->no_voucher = generate_no_voucher($this->coa_id[$key]);
    }

    public function saveAsDraft()
    {   
        if($this->is_readonly) return false;
        $this->save();
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('operation');
    }

    public function save()
    {
        if($this->is_readonly) return false;

        $this->data->recipient = $this->recipient;
        $this->data->reference_type = $this->reference_type;
        $this->data->reference_date = $this->reference_date;
        $this->data->reference_no = $this->reference_no;
        $this->data->description = $this->description;
        $this->data->payment_date = $this->date;
        $this->data->user_id = \Auth::user()->id;
        $this->data->status = 1;
        $this->data->rekening_bank_id = $this->bank_account_id;
        $this->data->total_debit = $this->total_debit;
        $this->data->total_kredit = $this->total_kredit;
        $this->data->nominal = $this->total_kredit;
        $this->data->save();
        
        foreach($this->coa_id as $key => $val){
            if($val=="") continue;
            
            if(isset($this->expense_coa_id[$key]) and !empty($this->expense_coa_id[$key])){
                $coa  = \App\Models\ExpenseCoa::find($this->expense_coa_id[$key]);
            }else{
                $coa  = new \App\Models\ExpenseCoa();
                $coa->expense_id = $this->data->id;
                $coa->coa_id = $val;
            }
            
            $coa->payment_date = date('Y-m-d',strtotime($this->payment_date[$key]));
            $coa->debit = replace_idr(isset($this->debit[$key])?$this->debit[$key]:0);
            $coa->kredit = replace_idr(isset($this->kredit[$key])?$this->kredit[$key]:0);
            $coa->description = isset($this->description_coa[$key])?$this->description_coa[$key]:'';
            $coa->save();
        }
    }

    public function saveToJournal()
    {
        if($this->is_readonly) return false;

        $this->validate([
            'bank_account_id'=>'required',
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
            $data->transaction_table = 'expenses'; 
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