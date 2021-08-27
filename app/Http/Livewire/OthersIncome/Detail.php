<?php

namespace App\Http\Livewire\OthersIncome;

use Livewire\Component;

class Detail extends Component
{
    public $from_bank_account_id,$type=1,$no_voucher,$client,$reference_type,$reference_no,$reference_date,$description,$description_payment,$nominal,$outstanding_balance=0,$payment_date,$payment_amount=0,$transaction_type;
    public $is_readonly=false,$to_bank_account_id,$is_submit=false;
    public $add_payment,$add_payment_id,$add_payment_amount,$add_payment_description,$add_payment_transaction_type,$data,$bank_charges;
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    public function render()
    {
        return view('livewire.others-income.detail');
    }
    public function mount($id)
    {
        $this->income = \App\Models\Income::find($id);
        $this->no_voucher = $this->income->no_voucher;
        $this->client = $this->income->client;
        $this->reference_type = $this->income->reference_type;
        $this->reference_no = $this->income->reference_no;
        $this->nominal = $this->income->nominal;
        $this->bank_charges = $this->income->bank_charges;
        $this->payment_date = $this->income->payment_date;
        $this->reference_date = $this->income->reference_date;
        $this->from_bank_account_id = $this->income->from_bank_account_id;
        $this->to_bank_account_id = $this->income->rekening_bank_id;
        $this->description = $this->income->description;
        foreach(\App\Models\IncomePayment::where('income_id',$id)->get() as $k => $item){
            $this->add_payment[$k] = $item->id;
            $this->add_payment_id[$k] = $item->id;
            $this->add_payment_amount[$k]=$item->payment_amount;
            $this->add_payment_description[$k]=$item->description;
            $this->add_payment_transaction_type[$k]=$item->transaction_type;
        }
        if($this->income->status==2) $this->is_readonly = true;
        $this->calculate();
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
                'client' => 'required',
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
        $data = new \App\Models\Income();
        $data->no_voucher = $this->no_voucher;
        $data->client = $this->client;
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
        $data->bank_charges = replace_idr($this->bank_charges);
        $data->save();
        foreach($this->add_payment as $k =>$i){
            $ex_payment = new \App\Models\IncomePayment();
            $ex_payment->income_id = $data->id;
            $ex_payment->payment_date = $this->payment_date;
            $ex_payment->payment_amount = replace_idr($this->add_payment_amount[$k]);
            $ex_payment->transaction_type = $this->add_payment_transaction_type[$k];
            $ex_payment->description = $this->add_payment_description[$k];
            $ex_payment->save();    
        }
        if($status==2){
            // set balance
            $bank_balance = \App\Models\BankAccount::find($data->to_bank_account_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance + $this->payment_amount;
                $bank_balance->save();
                $balance = new \App\Models\BankAccountBalance();
                $balance->kredit = $this->payment_amount;
                $balance->debit = 0;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 8; // Cancelation
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }
            // insert Journal
            if(isset($data->to_bank_account_id->coa->id)){
                $journal_no_voucher = generate_no_voucher($data->to_bank_account_id->coa->id,$data->id);
                $new  = new \App\Models\Journal();
                $new->transaction_number = $data->reference_no;
                $new->transaction_id = $data->id;
                $new->transaction_table = 'income'; 
                $new->coa_id = $data->to_bank_account_id->coa->id;
                $new->no_voucher = $journal_no_voucher;
                $new->date_journal = date('Y-m-d');
                $new->debit = $this->payment_amount;
                $new->kredit = 0;
                $new->saldo = $this->payment_amount;
                $new->description = $this->description;
                $new->save();

                foreach($this->add_payment as $k =>$i){
                    // insert Journal
                    $new  = new \App\Models\Journal();
                    $new->transaction_number = $data->reference_no;
                    $new->transaction_id = $data->id;
                    $new->transaction_table = 'income'; 
                    $new->coa_id = $this->add_payment_transaction_type[$k];
                    $new->no_voucher = $journal_no_voucher;
                    $new->date_journal = date('Y-m-d');
                    $new->kredit = replace_idr($this->add_payment_amount[$k]);
                    $new->debit = 0;
                    $new->saldo = replace_idr($this->add_payment_amount[$k]);
                    $new->description = $this->add_payment_description[$k];
                    $new->save();
                }
            }
        }
        \LogActivity::add("Income Others Submit {$data->id}");
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('others-income.index');
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
        \App\Models\IncomePayment::find($this->add_payment_id[$k])->delete();
        unset($this->add_payment[$k]);
        unset($this->add_payment_amount[$k]);
        unset($this->add_payment_description[$k]);
        unset($this->add_payment_transaction_type[$k]);
        $this->emit('init-form');
        $this->calculate();
    }
}
