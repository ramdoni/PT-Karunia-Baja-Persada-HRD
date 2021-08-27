<?php

namespace App\Http\Livewire\ExpenseOthers;

use Livewire\Component;

class Detail extends Component
{
    public $type,$data,$no_voucher,$recipient,$reference_type,$reference_no,$reference_date,$description,$description_payment,$nominal,$outstanding_balance=0,$payment_date,$payment_amount=0,$transaction_type;
    public $is_readonly=false,$from_bank_account_id,$to_bank_account_id;
    public $add_payment=[],$add_payment_id=[],$add_payment_amount,$add_payment_description,$add_payment_transaction_type;
    public $add_payment_temp=[],$add_payment_amount_temp,$add_payment_description_temp,$add_payment_transaction_type_temp;
    public function render()
    {
        return view('livewire.expense-others.detail');
    }
    public function mount($id)
    {
        $this->data = \App\Models\Expenses::find($id);
        $this->no_voucher = generate_no_voucher_expense();
        $this->recipient = $this->data->recipient;
        $this->payment_date = $this->data->payment_date;
        $this->reference_date = $this->data->reference_date;     
        $this->reference_type = $this->data->reference_type;     
        $this->reference_no = $this->data->reference_no;        
        $this->nominal = $this->data->nominal;
        $this->from_bank_account_id = $this->data->from_bank_account_id;
        $this->to_bank_account_id = $this->data->rekening_bank_id;
        $this->payment_amount = $this->data->payment_amount;
        $this->type = $this->data->type;
        $this->description = $this->data->description;
        foreach(\App\Models\ExpensePayment::where('expense_id',$this->data->id)->get() as $k=>$item){
            $this->add_payment[$k] = '';
            $this->add_payment_id[$k]=$item->id;
            $this->add_payment_amount[$k]=$item->payment_amount;
            $this->add_payment_description[$k]=$item->description;
            $this->add_payment_transaction_type[$k]=$item->transaction_type;
        }
        $this->calculate();
        \LogActivity::add("Expense Others Detail {$this->data->id}");
    }
    public function updated($propertyName)
    {
        $this->calculate();
        $this->emit('init-form');    
    }
    public function delete($key)
    {
        \App\Models\ExpensePayment::find($this->add_payment_id[$key])->delete();
        unset($this->add_payment[$key]);
        unset($this->add_payment_id[$key]);
        unset($this->add_payment_amount[$key]);
        unset($this->add_payment_description[$key]);
        unset($this->add_payment_transaction_type[$key]);
        $this->emit('init-form');
    }
    public function calculate()
    {
        $this->payment_amount =  0;
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
                'reference_type' => 'required',
                'reference_no' => 'required',
                'reference_date' => 'required',
                'nominal' => 'required',
                'payment_amount' => 'required',
                'payment_date'=>'required',
            ]
        );
        $this->nominal = replace_idr($this->nominal);
        $this->outstanding_balance = replace_idr($this->outstanding_balance);
        if($this->payment_amount==$this->nominal || $this->payment_amount>$this->nominal) $status=2; // Paid
        if($this->payment_amount<$this->nominal) $status=3; // Outstanding
        if($type=='Draft') $status=4; // Draft
        $this->data->no_voucher = $this->no_voucher;
        $this->data->recipient = $this->recipient;
        $this->data->user_id = \Auth::user()->id;
        $this->data->reference_type = $this->reference_type;
        $this->data->reference_date = $this->reference_date;
        $this->data->description = $this->description;
        $this->data->nominal = replace_idr($this->nominal);
        $this->data->outstanding_balance = replace_idr($this->outstanding_balance);
        $this->data->reference_no = $this->reference_no;
        $this->data->payment_amount = $this->payment_amount;
        $this->data->status = $status;
        $this->data->is_others = 1;
        $this->data->rekening_bank_id = $this->to_bank_account_id;
        $this->data->from_bank_account_id = $this->from_bank_account_id;
        $this->data->save();

        foreach($this->add_payment as $k =>$i){
            $ex_payment = \App\Models\ExpensePayment::find($this->add_payment_id[$k]);
            $ex_payment->payment_date = $this->payment_date;
            $ex_payment->payment_amount = replace_idr($this->add_payment_amount[$k]);
            $ex_payment->transaction_type = $this->add_payment_transaction_type[$k];
            $ex_payment->description = $this->add_payment_description[$k];
            $ex_payment->save();
        }
        foreach($this->add_payment_temp as $k =>$i){
            $ex_payment = new \App\Models\ExpensePayment();
            $ex_payment->expense_id = $this->data->id;
            $ex_payment->payment_date = $this->payment_date;
            $ex_payment->payment_amount = replace_idr($this->add_payment_amount_temp[$k]);
            $ex_payment->transaction_type = $this->add_payment_transaction_type_temp[$k];
            $ex_payment->description = $this->add_payment_description_temp[$k];
            $ex_payment->save();
        }
        if($status==2){
            // insert Journal
            if(isset($data->from_bank_account->coa->id)){
                $journal_no_voucher = generate_no_voucher($this->data->from_bank_account->coa->id,$this->data->id);

                $new  = new \App\Models\Journal();
                $new->transaction_number = $this->data->reference_no;
                $new->transaction_id = $this->data->id;
                $new->transaction_table = 'expenses'; 
                $new->coa_id = $this->data->from_bank_account->coa->id;
                $new->no_voucher = $journal_no_voucher;
                $new->date_journal = date('Y-m-d');
                $new->kredit = $this->payment_amount;
                $new->debit = 0;
                $new->saldo = $this->payment_amount;
                $new->description = $this->description;
                $new->save();

                // insert Journal
                foreach(\App\Models\ExpensePayment::where('expense_id',$this->data->id)->get() as $k=>$item){
                    // insert Journal
                    $new  = new \App\Models\Journal();
                    $new->transaction_number = $this->data->reference_no;
                    $new->transaction_id = $this->data->id;
                    $new->transaction_table = 'expenses'; 
                    $new->coa_id = $item->transaction_type;
                    $new->no_voucher = $journal_no_voucher;
                    $new->date_journal = date('Y-m-d');
                    $new->debit = $item->payment_amount;
                    $new->kredit = 0;
                    $new->saldo = $item->payment_amount;
                    $new->description = $item->description;
                    $new->save();
                }
            }
        }
        \LogActivity::add("Expense Others Submit {$this->data->id}");
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('expense.others');
    }
    public function addPayment() 
    {
        $this->add_payment_temp[] = '';
        $this->add_payment_amount_temp[] = 0;
        $this->add_payment_description_temp[] = '';
        $this->add_payment_transaction_type_temp[] = '';
        $this->emit('init-form');
    }
    public function deleteTemp($k)
    {
        unset($this->add_payment_temp[$k]);
        unset($this->add_payment_amount_temp[$k]);
        unset($this->add_payment_description_temp[$k]);
        unset($this->add_payment_transaction_type_temp[$k]);
        $this->emit('init-form');
        $this->calculate();
    }
}
