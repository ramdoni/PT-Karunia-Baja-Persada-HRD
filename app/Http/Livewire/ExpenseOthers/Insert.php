<?php

namespace App\Http\Livewire\ExpenseOthers;

use Livewire\Component;

class Insert extends Component
{
    public $from_bank_account_id,$type=1,$no_voucher,$recipient,$reference_type,$reference_no,$reference_date,$description,$description_payment,$nominal,$outstanding_balance=0,$payment_date,$payment_amount=0,$transaction_type;
    public $is_readonly=false,$to_bank_account_id;
    public $add_payment,$add_payment_id,$add_payment_amount,$add_payment_description,$add_payment_transaction_type;
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    public function render()
    {
        return view('livewire.expense-others.insert');
    }
    public function mount()
    {
        $this->no_voucher = generate_no_voucher_expense();
        $this->payment_date = date('Y-m-d');
        $this->reference_date = date('Y-m-d');
        $this->add_payment[] = '';
        $this->add_payment_id[]='';
        $this->add_payment_amount[]=0;
        $this->add_payment_description[]='';
        $this->add_payment_transaction_type[]='';
    }
    public function updated($propertyName)
    {
        $this->calculate();
        $this->emit('init-form');    
    }
    public function emitAddBank($id)
    {
        $this->to_bank_account_id = $id;
        $this->emit('init-form');
    }
    public function calculate()
    {
        $this->payment_amount = 0;
        foreach($this->add_payment as $k => $i){
            $this->payment_amount += replace_idr($this->add_payment_amount[$k]);
        }
        $this->outstanding_balance = format_idr(abs(replace_idr($this->payment_amount) - replace_idr($this->nominal)));
    }
    public function save($type)
    {
        $this->validate(
            [
                'recipient' => 'required',
                'reference_no' => 'required',
                'reference_date' => 'required',
                'reference_type' => 'required',
                'nominal' => 'required',
                'payment_date'=>'required',
                'from_bank_account_id'=>'required',
                'to_bank_account_id'=>'required',
            ]
        );
        $this->nominal = replace_idr($this->nominal);
        $this->outstanding_balance = replace_idr($this->outstanding_balance);
        if($this->payment_amount==$this->nominal || $this->payment_amount>$this->nominal) $status=2; // Paid
        if($this->payment_amount<$this->nominal) $status=3; // Outstanding
        if($type=='Draft') $status=4; // Draft
        $data = new \App\Models\Expenses();
        $data->no_voucher = $this->no_voucher;
        $data->recipient = $this->recipient;
        $data->user_id = \Auth::user()->id;
        $data->reference_type = $this->reference_type;
        $data->reference_date = $this->reference_date;
        $data->description = $this->description;
        $data->nominal = replace_idr($this->nominal);
        $data->outstanding_balance = replace_idr($this->outstanding_balance);
        $data->reference_no = $this->reference_no;
        $data->payment_amount = $this->payment_amount;
        $data->status = $status;
        $data->rekening_bank_id = $this->to_bank_account_id;
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->is_others = 1;
        $data->payment_date = $this->payment_date;
        $data->save();
        foreach($this->add_payment as $k =>$i){
            $ex_payment = new \App\Models\ExpensePayment();
            $ex_payment->expense_id = $data->id;
            $ex_payment->payment_date = $this->payment_date;
            $ex_payment->payment_amount = replace_idr($this->add_payment_amount[$k]);
            $ex_payment->transaction_type = $this->add_payment_transaction_type[$k];
            $ex_payment->description = $this->add_payment_description[$k];
            $ex_payment->save();    
        }
        if($status==2){
            // set balance
            $bank_balance = \App\Models\BankAccount::find($data->from_bank_account_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance - $this->payment_amount;
                $bank_balance->save();
                $balance = new \App\Models\BankAccountBalance();
                $balance->debit = $this->payment_amount;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 8; // Cancelation
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }
            // insert Journal
            if(isset($data->from_bank_account->coa->id)){
                $journal_no_voucher = generate_no_voucher($data->from_bank_account->coa->id,$data->id);
                $new  = new \App\Models\Journal();
                $new->transaction_number = $data->reference_no;
                $new->transaction_id = $data->id;
                $new->transaction_table = 'expenses'; 
                $new->coa_id = $data->from_bank_account->coa->id;
                $new->no_voucher = $journal_no_voucher;
                $new->date_journal = date('Y-m-d');
                $new->kredit = $this->payment_amount;
                $new->debit = 0;
                $new->saldo = $this->payment_amount;
                $new->description = $this->description;
                $new->save();

                foreach($this->add_payment as $k =>$i){
                    // insert Journal
                    $new  = new \App\Models\Journal();
                    $new->transaction_number = $data->reference_no;
                    $new->transaction_id = $data->id;
                    $new->transaction_table = 'expenses'; 
                    $new->coa_id = $this->add_payment_transaction_type[$k];
                    $new->no_voucher = $journal_no_voucher;
                    $new->date_journal = date('Y-m-d');
                    $new->debit = replace_idr($this->add_payment_amount[$k]);
                    $new->kredit = 0;
                    $new->saldo = replace_idr($this->add_payment_amount[$k]);
                    $new->description = $this->add_payment_description[$k];
                    $new->save();
                }
            }
        }
        \LogActivity::add("Expense Others Submit {$data->id}");
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('expense.others');
    }
    public function addPayment() 
    {
        $this->add_payment[] = '';
        $this->add_payment_amount[] = 0;
        $this->add_payment_description[] = '';
        $this->add_payment_transaction_type[] = '';
        $this->emit('init-form');
    }
    public function delete($k)
    {
        unset($this->add_payment[$k]);
        unset($this->add_payment_amount[$k]);
        unset($this->add_payment_description[$k]);
        unset($this->add_payment_transaction_type[$k]);
        $this->emit('init-form');
        $this->calculate();
    }
}
