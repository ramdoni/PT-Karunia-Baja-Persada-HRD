<?php

namespace App\Http\Livewire\ExpenseCommisionPayable;

use Livewire\Component;

class Detail extends Component
{
    public $expense,$data,$no_voucher,$no_polis,$nilai_klaim,$premium_receivable,$is_submit=true,$is_readonly=false;
    public $reference_no,$bank_charges,$description,$type=1;
    public $transaction_type=[],$payments=[],$payment_amount,$payment_id=[],$payment_date=[],$nama=[],$no_rekening=[],$bank=[];
    public $transaction_type_temp=[],$payments_temp=[],$payment_amount_temp=[],$payment_date_temp=[],$nama_temp=[],$no_rekening_temp=[],$bank_temp=[];
    public function render()
    {
        return view('livewire.expense-commision-payable.detail');
    }
    public function mount($id)
    {
        $this->expense  = \App\Models\Expenses::find($id);
        $this->data = \App\Models\Policy::find($this->expense->policy_id);
        $this->no_voucher = $this->expense->no_voucher;
        $this->reference_no = $this->expense->reference_no;
        $this->no_polis = $this->expense->policy_id;
        $this->payments = \App\Models\ExpensePayment::where('expense_id',$id)->get();
        foreach($this->payments as $k => $payment){
            $this->payment_id[$k] = $payment->id;
            $this->transaction_type[$k] = $payment->transaction_type;
            $this->payment_amount[$k] = $payment->payment_amount;
            $this->payment_date[$k] = $payment->payment_date;
            $bank = json_decode($payment->description);
            $this->nama[$k] = isset($bank->nama) ? $bank->nama : '';
            $this->no_rekening[$k] = isset($bank->no_rekening) ? $bank->no_rekening : '';
            $this->bank[$k] = isset($bank->bank) ? $bank->bank : '';
        }
        $premium = \App\Models\Income::select('income.*')->where(['income.reference_type'=>'Premium Receivable','income.transaction_table'=>'konven_underwriting'])
                                            ->join('konven_underwriting','konven_underwriting.id','=','income.transaction_id')
                                            ->where('konven_underwriting.no_polis',$this->data->no_polis);
        $total_premium_receive = clone $premium;
        if($total_premium_receive->where('income.status',2)->sum('income.payment_amount') > 0) $this->is_submit = true;
        else $this->is_submit = false;
        $this->is_submit = true;

        if($this->expense->status==2) $this->is_readonly = true;

        $this->premium_receivable = $premium->get();
    }
    public function updated($propertyName)
    {
        foreach($this->payments as $k => $payment){
            if(
                $propertyName =="transaction_type.{$k}" ||
                $propertyName =="nama.{$k}" ||
                $propertyName =="no_rekening.{$k}" ||
                $propertyName =="bank.{$k}" ||
                $propertyName =="payment_amount.{$k}" ||   
                $propertyName =="payment_date.{$k}"
            ){
                $description = json_encode(['nama'=>$this->nama[$k],'no_rekening'=>$this->no_rekening[$k],'bank'=>$this->bank[$k]]);
                \App\Models\ExpensePayment::find($this->payment_id[$k])->update(['transaction_type'=>$this->transaction_type[$k],
                                                                                'payment_amount'=>$this->payment_amount[$k],
                                                                                'payment_date'=>$this->payment_date[$k],
                                                                                'description'=>$description]);
            }
        }
        $this->emit('init-form');
    }
    public function delete_payment($k)
    {
        \App\Models\ExpensePayment::find($this->payment_id[$k])->delete();
        $this->payments = \App\Models\ExpensePayment::where('expense_id',$this->expense->id)->get();
    }
    public function delete_payment_temp($k)
    {
        unset($this->payments_temp[$k],$this->transaction_type_temp[$k],$this->payment_amount_temp[$k],$this->payment_date_temp[$k],$this->nama_temp[$k],$this->no_rekening_temp[$k],$this->bank_temp[$k]);
        $this->emit('init-form');
    }
    public function add_payment()
    {
        $this->payments_temp[]= count($this->payments_temp);
        $this->transaction_type_temp[] = '';
        $this->payment_amount_temp[] = 0;
        $this->payment_date_temp[] = '';
        $this->nama_temp[] = '';
        $this->no_rekening_temp[] = '';
        $this->bank_temp[] = '';
        $this->emit('init-form');
    }
    public function submit()
    {
        $this->save('Submit');
    }
    public function save($type)
    {
        $this->expense->status = $type=='Draft' ? 4 : 2;
        $this->expense->save();
        
        if($this->payments_temp){
            foreach($this->payments_temp as $k => $val){
                $payment = new \App\Models\ExpensePayment();
                $payment->expense_id = $this->expense->id;
                $payment->payment_amount = replace_idr($this->payment_amount_temp[$k]);
                $payment->transaction_type = $this->transaction_type_temp[$k];
                $payment->payment_date = $this->payment_date_temp[$k];
                $payment->description = json_encode(['nama'=>$this->nama_temp[$k],'no_rekening'=>$this->no_rekening_temp[$k],'bank'=>$this->bank_temp[$k]]);
                $payment->save();
            }
        }
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('expense.commision-payable');
    }
    public function saveAsDraft()
    {
        $this->save('Draft');
    }
}