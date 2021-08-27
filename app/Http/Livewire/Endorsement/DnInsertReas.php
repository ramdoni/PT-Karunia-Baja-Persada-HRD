<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;

class DnInsertReas extends Component
{
    public $data,$no_voucher,$no_polis,$payment_amount,$premium_receivable,$is_submit=false;
    public $reference_no,$to_bank_account_id,$from_bank_account_id,$payment_date,$bank_charges,$description,$type=1;
    public function render()
    {
        return view('livewire.endorsement.dn-insert-reas');
    }
    public function mount()
    {
        $this->no_voucher = generate_no_voucher_expense();
    }
    public function updated($propertyName)
    {
        if($propertyName=='no_polis'){
            $this->data = \App\Models\Policy::find($this->no_polis);
            $premium = \App\Models\Income::select('income.*')->where(['income.reference_type'=>'Premium Receivable','income.transaction_table'=>'konven_underwriting'])
                                            ->join('konven_underwriting','konven_underwriting.id','=','income.transaction_id')
                                            ->where('konven_underwriting.no_polis',$this->data->no_polis);
            $total_premium_receive = clone $premium;
            if($total_premium_receive->where('income.status',2)->sum('income.payment_amount') > 0) $this->is_submit = true;
            else $this->is_submit = false;

            $this->premium_receivable = $premium->get();
        }
        $this->emit('init-form');
    }
    public function save($type)
    {
        $this->bank_charges = replace_idr($this->bank_charges);
        $this->payment_amount = replace_idr($this->payment_amount);
        $this->validate(
            [
                'no_polis' => 'required',
                'payment_amount' => 'required',
                'payment_date' => 'required',
                'from_bank_account_id' => 'required',
                'to_bank_account_id' => 'required'
            ]);
        $data = new \App\Models\Income();
        $data->policy_id = $this->data->id;
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->rekening_bank_id = $this->to_bank_account_id;
        $data->reference_type = 'Endorsement DN Reas';
        $data->reference_no = $this->reference_no;
        $data->client = $this->data->no_polis.' - '. $this->data->pemegang_polis;
        $data->no_voucher = $this->no_voucher;
        $data->payment_amount = $this->payment_amount;
        $data->nominal = $this->payment_amount;
        $data->payment_date = $this->payment_date;
        $data->bank_charges = $this->bank_charges;
        $data->status = $type=='Draft' ? 5 : 2;
        $data->user_id = \Auth::user()->id;
        $data->description = $this->description;
        $data->type = $this->type;
        $data->save();

        if($type=='Submit'){
            // set balance
            $bank_balance = \App\Models\BankAccount::find($data->from_bank_account_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance + $this->payment_amount;
                $bank_balance->save();

                $balance = new \App\Models\BankAccountBalance();
                $balance->kredit = $this->payment_amount;
                $balance->debit = 0;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 9; // Endorsement Reas
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }
        }
        session()->flash('message-success',__('Endorsement data has been successfully saved'));
        \LogActivity::add("Endorsement DN Reas {$type} {$data->id}");
        return redirect()->route('endorsement.index');
    }
}
