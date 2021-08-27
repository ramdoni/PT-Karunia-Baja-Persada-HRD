<?php

namespace App\Http\Livewire\ExpenseClaim;

use Livewire\Component;
use App\Models\Income;
use App\Models\Policy;
use App\Models\Expenses;
use App\Models\ExpensePeserta;

class Detail extends Component
{
    public $expense,$data,$no_voucher,$no_polis,$nilai_klaim,$premium_receivable,$is_submit=false,$is_readonly=false;
    public $reference_no,$to_bank_account_id,$from_bank_account_id,$payment_date,$bank_charges,$description,$type=1;
    public $add_pesertas=[],$add_peserta_id=[],$no_peserta=[],$nama_peserta=[];
    public $add_pesertas_temp=[],$no_peserta_temp=[],$nama_peserta_temp=[];
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];
    public function render()
    {
        return view('livewire.expense-claim.detail');
    }
    public function mount($id)
    {
        $this->expense = Expenses::find($id);
        $this->no_polis = $this->expense->policy_id;
        $this->no_voucher = $this->expense->no_voucher;
        $this->type = $this->expense->type;
        $this->from_bank_account_id = $this->expense->from_bank_account_id;
        $this->to_bank_account_id = $this->expense->rekening_bank_id;
        $this->nilai_klaim = $this->expense->payment_amount;
        $this->bank_charges = $this->expense->bank_charges;
        $this->payment_date = $this->expense->payment_date;
        $this->reference_no = $this->expense->reference_no;
        $this->description = $this->expense->description;
        $this->data = Policy::find($this->expense->policy_id);
        
        $premium = '';
        $this->add_pesertas = ExpensePeserta::where('expense_id',$id)->get();
        foreach($this->add_pesertas as $k => $i){
            $this->add_peserta_id[$k] = $i->id;
            $this->no_peserta[$k] = $i->no_peserta;
            $this->nama_peserta[$k] = $i->nama_peserta;
        }
        
        if($this->data){
            $premium = Income::select('income.*')->where(['income.reference_type'=>'Premium Receivable','income.transaction_table'=>'konven_underwriting'])
                                            ->join('konven_underwriting','konven_underwriting.id','=','income.transaction_id')
                                            ->where('konven_underwriting.no_polis',$this->data->no_polis);
        
            $total_premium_receive = clone $premium;
            
            if(isset($total_premium_receive) and $total_premium_receive->where('income.status',2)->sum('income.payment_amount') > 0) 
                $this->is_submit = true;
            else 
                $this->is_submit = false;

            if($this->expense->status==2) $this->is_readonly=true;
            $this->premium_receivable = $premium->get();
        }
    }

    public function emitAddBank($id)
    {
        $this->to_bank_account_id = $id;
        $this->emit('init-bank');
    }
    
    public function delete_peserta($k)
    {
        if($this->add_peserta_id[$k]){
            \App\Models\ExpensePeserta::find($this->add_peserta_id[$k])->delete();
            unset($this->add_pesertas[$k],$this->add_peserta_id[$k],$this->no_peserta[$k],$this->nama_peserta[$k]);
        }
    }
    public function delete_peserta_temp($key)
    {
        unset($this->add_pesertas_temp[$key],$this->no_peserta_temp[$key],$this->nama_peserta_temp[$key]);
    }
    public function add_peserta()
    {
        $this->add_pesertas_temp[] = count($this->add_pesertas_temp);
        $this->no_peserta_temp[] = '';
        $this->nama_peserta_temp[] = '';
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
        
        $this->emit('init-form');
        
        if($type =='Draft'){
            $this->validate([
                'no_polis' => 'required|not_in:0',
            ]);
        }else{
            $this->validate([
                'no_polis' => 'required|not_in:0',
                'nilai_klaim' => 'required',
                'payment_date' => 'required',
                'from_bank_account_id' => 'required'
            ]);
        }

        $this->data = Policy::find($this->no_polis);

        $this->expense->policy_id = $this->data->id;
        $this->expense->from_bank_account_id = $this->from_bank_account_id;
        $this->expense->rekening_bank_id = $this->to_bank_account_id;
        $this->expense->reference_no = $this->reference_no;
        $this->expense->recipient = $this->data->no_polis.' - '. $this->data->pemegang_polis;
        $this->expense->no_voucher = $this->no_voucher;
        $this->expense->payment_amount = $this->nilai_klaim;
        $this->expense->payment_date = $this->payment_date;
        $this->expense->bank_charges = $this->bank_charges;
        $this->expense->status = $type=='Draft' ? 4 : 2;
        $this->expense->description = $this->description;
        $this->expense->type = $this->type;
        $this->expense->save();
        
        if($this->add_pesertas_temp){
            foreach($this->add_pesertas_temp as $k=>$v){
                if(!empty($this->no_peserta_temp[$k]) and !empty($this->nama_peserta_temp[$k])){
                    $peserta = new \App\Models\ExpensePeserta();
                    $peserta->expense_id = $this->expense->id;
                    $peserta->no_peserta = $this->no_peserta_temp[$k];
                    $peserta->nama_peserta = $this->nama_peserta_temp[$k];
                    $peserta->type = 1; // Claim Payable
                    $peserta->policy_id = $this->data->id;
                    $peserta->save();
                }
            }
        }
        if($this->add_pesertas){
            foreach($this->add_pesertas as $k=>$v){
                if(!empty($this->no_peserta[$k]) and !empty($this->nama_peserta[$k])){
                    $peserta = \App\Models\ExpensePeserta::find($this->add_peserta_id[$k]);
                    $peserta->expense_id = $this->expense->id;
                    $peserta->no_peserta = $this->no_peserta[$k];
                    $peserta->nama_peserta = $this->nama_peserta[$k];
                    $peserta->policy_id = $this->data->id;
                    $peserta->save();
                }
            }
        }
        if($type=='Submit'){
            // set balance
            $bank_balance = \App\Models\BankAccount::find($this->expense->from_bank_account_id);
            if($bank_balance){
                $bank_balance->open_balance = $bank_balance->open_balance - $this->nilai_klaim;
                $bank_balance->save();

                $balance = new \App\Models\BankAccountBalance();
                $balance->debit = $this->nilai_klaim;
                $balance->bank_account_id = $bank_balance->id;
                $balance->status = 1;
                $balance->type = 6; // Claim Payable
                $balance->nominal = $bank_balance->open_balance;
                $balance->transaction_date = $this->payment_date;
                $balance->save();
            }
        }

        session()->flash('message-success',__('Claim data has been successfully saved'));
        \LogActivity::add("Expense Claim {$type} {$this->expense->id}");
        return redirect()->route('expense.claim');
    }
}
