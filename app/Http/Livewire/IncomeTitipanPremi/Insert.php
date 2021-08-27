<?php

namespace App\Http\Livewire\IncomeTitipanPremi;

use Livewire\Component;

class Insert extends Component
{
    public $no_voucher,$reference_no,$type,$payment_amount,$payment_date,$reference_type,$description,$data,$nominal,$from_bank_account_id,$to_bank_account_id,$is_readonly=false;
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    public function render()
    {
        return view('livewire.income-titipan-premi.insert');
    }
    public function mount()
    { 
        $this->no_voucher = generate_no_voucher_income();
    }
    public function updated()
    {
        $this->emit('init-form');
    }
    public function emitAddBank($id)
    {
        $this->from_bank_account_id = $id;
        $this->emit('init-form');
    }
    public function save()
    {
        $this->validate(
            [   
                'nominal' => 'required',
                'to_bank_account_id' => 'required',
                'payment_date' => 'required'
            ]);

        $this->nominal = replace_idr($this->nominal);
        
        $data = new \App\Models\Income();
        $data->no_voucher = $this->no_voucher;
        $data->payment_date = $this->payment_date;
        $data->reference_no = $this->reference_no;
        $data->reference_type = 'Titipan Premi';
        $data->nominal = $this->nominal;
        $data->outstanding_balance = $data->nominal;
        $data->description = $this->description;
        $data->rekening_bank_id = $this->to_bank_account_id;
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->user_id = \Auth::user()->id;
        $data->type = $this->type;
        $data->save();
        
        # insert journal
        $coa_bank_account = \App\Models\BankAccount::find($this->to_bank_account_id);
        if($coa_bank_account and !empty($coa_bank_account->coa_id)){
            $journal = new \App\Models\Journal();
            $journal->coa_id = get_coa(406000); // premium suspend;
            $journal->no_voucher = generate_no_voucher(get_coa(406000),$data->id);
            $journal->date_journal = date('Y-m-d');
            $journal->kredit = $this->nominal;
            $journal->debit = 0;
            $journal->saldo = $this->nominal;
            $journal->description = $this->description;
            $journal->transaction_id = $data->id;
            $journal->transaction_table = 'income';
            $journal->transaction_number = $this->reference_no;
            $journal->save();

            $journal = new \App\Models\Journal();
            $journal->coa_id = $coa_bank_account->coa_id;
            $journal->no_voucher = generate_no_voucher($coa_bank_account->coa_id,$data->id);
            $journal->date_journal = date('Y-m-d');
            $journal->debit = $this->nominal;
            $journal->kredit = 0;
            $journal->saldo = $this->nominal;
            $journal->description = $this->description;
            $journal->transaction_id = $data->id;
            $journal->transaction_table = 'income';
            $journal->transaction_number = $this->reference_no;
            $journal->save();
        }

        \LogActivity::add("Income Titipan Premi Insert {$data->id}");
        
        session()->flash('message-success',__('Data saved successfully'));
        
        return redirect()->route('income.titipan-premi');
    }
}
