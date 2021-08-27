<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;

class DnDetailReas extends Component
{
    public $expense,$data,$no_voucher,$no_polis,$nilai_klaim,$premium_receivable,$is_submit=false,$is_readonly=false;
    public $reference_no,$to_bank_account_id,$from_bank_account_id,$payment_date,$bank_charges,$description,$type=1;
    public function render()
    {
        return view('livewire.endorsement.dn-detail-reas');
    }
    public function mount($id)
    {
        $this->expense = \App\Models\Income::find($id);
        $this->no_polis = $this->expense->policy_id;
        $this->no_voucher = $this->expense->no_voucher;
        $this->type = $this->expense->type;
        $this->from_bank_account_id = $this->expense->from_bank_account_id;
        $this->to_bank_account_id = $this->expense->rekening_bank_id;
        $this->nilai_klaim = $this->expense->payment_amount;
        $this->bank_charges = $this->expense->bank_charges;
        $this->payment_date = $this->expense->payment_date;
        $this->reference_no = $this->expense->reference_no;
        $this->data = \App\Models\Policy::find($this->expense->policy_id);
        $premium = \App\Models\Income::select('income.*')->where(['income.reference_type'=>'Premium Receivable','income.transaction_table'=>'konven_underwriting'])
                                            ->join('konven_underwriting','konven_underwriting.id','=','income.transaction_id')
                                            ->where('konven_underwriting.no_polis',$this->data->no_polis);
        $total_premium_receive = clone $premium;
        if($total_premium_receive->where('income.status',2)->sum('income.payment_amount') > 0) $this->is_submit = true;
        else $this->is_submit = false;

        if($this->expense->status==2) $this->is_readonly=true;

        $this->premium_receivable = $premium->get();
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
        $this->nilai_klaim = replace_idr($this->nilai_klaim);
        $this->validate(
            [
                'no_polis' => 'required',
                'nilai_klaim' => 'required',
                'payment_date' => 'required',
                'from_bank_account_id' => 'required',
                'to_bank_account_id' => 'required'
            ]);
        $this->expense->from_bank_account_id = $this->from_bank_account_id;
        $this->expense->rekening_bank_id = $this->to_bank_account_id;
        $this->expense->reference_no = $this->reference_no;
        $this->expense->client = $this->data->no_polis.' - '. $this->data->pemegang_polis;
        $this->expense->no_voucher = $this->no_voucher;
        $this->expense->payment_amount = $this->payment_amount;
        $this->expense->payment_date = $this->payment_date;
        $this->expense->bank_charges = $this->bank_charges;
        $this->expense->nominal = $this->payment_amount;
        $this->expense->status = $type=='Draft' ? 4 : 2;
        $this->expense->description = $this->description;
        $this->expense->type = $this->type;
        $this->expense->save();
        if($type=='Submit'){
            // set balance
            $bank_balance = \App\Models\BankAccount::find($this->expense->from_bank_account_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance + $this->payment_amount;
                $bank_balance->save();

                $balance = new \App\Models\BankAccountBalance();
                $balance->kredit = $this->payment_amount;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 6; // Claim Payable
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }
        }
        session()->flash('message-success',__('Endorsement data has been successfully saved'));
        \LogActivity::add("Endorsement DN Reas {$type} {$this->expense->id}");
        return redirect()->route('endorsement.index');
    }
}
