<?php

namespace App\Http\Livewire\IncomeRecoveryClaim;

use Livewire\Component;
use App\Models\Income;
use App\Models\Expenses;
use App\Models\IncomeRecoveryClaim;
use App\Models\IncomeTitipanPremi;

class Insert extends Component
{
    public $type=1,$no_voucher,$is_submit=true,$data,$premium_receivable,$expense_id,$outstanding_balance,$reference_no,$payment_amount,$from_bank_account_id,$to_bank_account_id;
    public $is_readonly=false,$payment_date,$bank_charges,$description,$reference_date;
    public $add_pesertas=[],$no_peserta=[],$nama_peserta=[];
    public $add_claim_payables=[],$add_expense_id=[];
    public $titipan_premi,$temp_titipan_premi=[],$temp_arr_titipan_id=[],$total_titipan_premi=0;

    protected $listeners = ['emit-add-bank'=>'emitAddBank','set-titipan-premi'=>'setTitipanPremi'];

    public function render()
    {
        return view('livewire.income-recovery-claim.insert');
    }

    public function mount()
    {
        $this->no_voucher = generate_no_voucher_income();
        $this->payment_date = date('Y-m-d');
        $this->reference_date = date('Y-m-d');
    }
    
    public function clearTitipanPremi()
    {
        $this->reset('temp_titipan_premi','temp_arr_titipan_id','total_titipan_premi','from_bank_account_id','to_bank_account_id');
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
        if($this->total_titipan_premi < $this->payment_amount){
            $this->payment_amount = $this->total_titipan_premi;
            $this->outstanding_balance = abs(replace_idr($this->payment_amount) - $this->nominal);
        }elseif($this->total_titipan_premi>$this->payment_amount){
            $this->payment_amount = $this->payment_amount;
            $this->outstanding_balance = 0;
        }
        $this->emit('init-form');
    }

    public function add_claim_payable()
    {
        $this->add_claim_payables[] = count($this->add_claim_payables);
        $this->add_expense_id[] = '';
        $this->emit('init-form');
    }

    public function emitAddBank($id)
    {
        $this->from_bank_account_id = $id;
        $this->emit('init-form');
    }

    public function updated($propertyName)
    {
        if($propertyName=='expense_id'){
            $this->data = Expenses::find($this->expense_id);
        }
        $this->emit('init-form');
    }

    public function save()
    {
        $this->validate([
            'type' => 'required',
            'expense_id' => 'required',
            'payment_amount' => 'required'
        ]);

        $this->payment_amount = replace_idr($this->payment_amount);
        $this->bank_charges = replace_idr($this->bank_charges);
        $data = new Income();
        $data->no_voucher = $this->no_voucher;
        $data->reference_type = 'Recovery Claim';
        $data->description = $this->description;
        $data->outstanding_balance = $this->outstanding_balance;
        $data->reference_no = $this->reference_no;
        $data->client = isset($this->data->policy->no_polis) ? $this->data->policy->no_polis .' / '. $this->data->policy->pemegang_polis : '';
        $data->rekening_bank_id = $this->to_bank_account_id;
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->reference_date = $this->reference_date;
        $data->status = 2;
        $data->user_id = \Auth::user()->id;
        $data->payment_amount = $this->payment_amount;
        $data->bank_charges = $this->bank_charges;
        $data->payment_date = $this->payment_date;
        $data->type = $this->type;
        $data->transaction_id = $this->expense_id;
        $data->transaction_table = 'expenses';
        $data->save();

        if($this->temp_titipan_premi){
            $total_titipan_premi = 0;
            foreach($this->temp_arr_titipan_id as $k => $val){
                $item = Income::find($val);
                $total_titipan_premi += $item->outstanding_balance;            
                if($total_titipan_premi < $this->payment_amount){
                    IncomeTitipanPremi::create([
                        'income_premium_id' => $data->id,
                        'income_titipan_id' => $item->id,
                        'nominal' => $item->nominal,
                        'transaction_type'=>'Recovery Claim'
                    ]);
                    $item->payment_amount = $item->nominal;
                    $item->outstanding_balance = $item->outstanding_balance - $item->nominal;
                    $item->status = 2;
                }elseif($total_titipan_premi > $this->payment_amount){ // jika sudah melebihi nominal premi, maka status titipan premi jadi completed
                    $total_titipan_premi -= $item->outstanding_balance;
                    IncomeTitipanPremi::create([
                        'income_premium_id' => $data->id,
                        'income_titipan_id' => $item->id,
                        'nominal' => ($data->payment_amount - $total_titipan_premi),
                        'transaction_type'=>'Recovery Claim'
                    ]);
                    $item->outstanding_balance = $item->outstanding_balance - ($data->payment_amount - $total_titipan_premi);
                    $item->payment_amount = $item->payment_amount + ($data->payment_amount - $total_titipan_premi);
                }
                $item->save();
            }
        }

        if($this->add_claim_payables){
            foreach($this->add_claim_payables as $k => $v){
                if($this->add_expense_id[$k]) 
                IncomeRecoveryClaim::create([
                    'income_id' => $data->id,
                    'expense_id' => $this->add_expense_id[$k]
                ]);
            }
        }
        
        \LogActivity::add("Income - Recovery Claim Submit {$this->data->id}");
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->route('income.recovery-claim');
    }
}
