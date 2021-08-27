<?php

namespace App\Http\Livewire\Income;

use Livewire\Component;

class Edit extends Component
{
    public $data;
    public $account_id,$count_account,$bank_account_id,$receive_amount=0,$date,$outstanding_balance;
    public $income_coa_id,$kredit,$debit,$description_coa,$coa_id,$no_voucher;
    public $total_kredit,$total_debit;
    public $client,$reference_type,$reference_no,$reference_date,$description;
    public $nominal,$tax_id,$payment_amount;
    public $tax_amount,$total_amount;
    public $is_readonly = false,$is_submit_journal=false;
    public function render()
    {
        return view('livewire.income.edit');
    }

    public function mount($id)
    {
        $this->data = \App\Models\Income::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->client = $this->data->client;
        $this->reference_type = $this->data->reference_type;
        $this->reference_no = $this->data->reference_no;
        $this->reference_date = $this->data->reference_date;
        $this->description = $this->data->description;
        $this->nominal = $this->data->nominal;
        $this->outstanding_balance = $this->data->outstanding_balance;
        $this->tax_amount = $this->data->tax_amount;
        $this->tax_id = $this->data->tax_id;
        $this->payment_amount = $this->data->payment_amount;
        $this->total_amount = $this->data->nominal + $this->data->tax_amount;
        foreach($this->data->coa as $k => $item){
            $this->income_coa_id[$k] = $item->id;
            $this->count_account[$k] = $k;
            $this->coa_id[$k] = $item->coa_id;
            $this->kredit[$k] = format_idr($item->kredit);
            $this->debit[$k] = format_idr($item->debit);
            $this->description_coa[$k] = $item->description;
        }

        if(count($this->data->coa)==0){
            $this->count_account[] = 1;
            $this->coa_id[] = "";
            $this->description_coa[] = "";
            $this->kredit[] = 0;
            $this->debit[] = 0;
        }
        if($this->data->status==2) $this->is_readonly=true;
        $this->sumDebit();
        $this->sumKredit();
    }

    public function addAccountForm()
    {
        $this->income_coa_id[] = '';
        $this->count_account[] = count($this->count_account);
        $this->coa_id[] = "";
        $this->description_coa[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;

        $this->emit('changeForm');
    }

    public function sumDebit()
    {
        if(!$this->debit) return false;

        $this->total_debit=0;
        foreach($this->debit as $i){
            $this->total_debit += replace_idr($i);
        }
        if($this->total_debit == $this->total_kredit and $this->total_debit == $this->total_amount) $this->is_submit_journal=true;
    }

    public function sumKredit() 
    {
        $this->total_kredit=0;
        foreach($this->kredit as $i){
            $this->total_kredit += replace_idr($i);
        }
        if($this->total_debit == $this->total_kredit and $this->total_debit == $this->total_amount) $this->is_submit_journal=true;
    }

    public function deleteAccountForm($key)
    {
        unset($this->count_account[$key]);
        unset($this->coa_id[$key]);
        unset($this->description_coa[$key]);
        unset($this->kredit[$key]);
        unset($this->debit[$key]);
        unset($this->income_coa_id[$key]);
        $this->sumDebit();
        $this->sumKredit();
        $this->emit('changeForm');
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

        return redirect()->to('income');
    }

    public function save()
    {
        if($this->is_readonly) return false;
       
        foreach($this->coa_id as $key => $val){
            
            if(isset($this->income_coa_id[$key]) and !empty($this->income_coa_id[$key])){
                $coa  = \App\Models\IncomeCoa::find($this->income_coa_id[$key]);
            }else{
                $coa  = new \App\Models\IncomeCoa();
                $coa->income_id = $this->data->id;
                $coa->coa_id = $val;
            }
            $coa->debit = replace_idr(isset($this->debit[$key])?$this->debit[$key]:0);
            $coa->kredit = replace_idr(isset($this->kredit[$key])?$this->kredit[$key]:0);
            $coa->description = isset($this->description_coa[$key])?$this->description_coa[$key]:'';
            $coa->save();
        }
    }

    public function saveToJournal()
    {
        if($this->is_submit_journal==false) return false;
        $this->save();
        $this->data->status = 2;
        $this->data->save();
        
        // save to journal
        foreach($this->coa_id as $key => $val){
            if($val=="") continue;
            $data  = new \App\Models\Journal();
            $data->transaction_number = $this->reference_no;
            $data->transaction_id = $this->data->id;
            $data->transaction_table = 'income'; 
            $data->coa_id = $val;
            $data->no_voucher = generate_no_voucher($val,$data->id);
            $data->date_journal = date('Y-m-d');
            $data->debit = replace_idr(isset($this->debit[$key])?$this->debit[$key]:0);
            $data->kredit = replace_idr(isset($this->kredit[$key])?$this->kredit[$key]:0);
            $data->description = isset($this->description_coa[$key])?$this->description_coa[$key]:'';
            $data->bank_account_id = $this->bank_account_id;
            $data->saldo = replace_idr(isset($this->debit[$key]) ? $this->debit[$key] : (isset($this->kredit[$key])?$this->kredit[$key] : 0));
            $data->save();
        }

        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('income');
    }
}
