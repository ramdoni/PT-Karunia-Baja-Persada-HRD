<?php

namespace App\Http\Livewire\IncomeRecoveryRefund;

use Livewire\Component;
use App\Models\Income;
use App\Models\IncomePeserta;
use App\Models\IncomeTitipanPremi;

class Insert extends Component
{
    public $type=1,$no_voucher,$is_submit=true,$data,$premium_receivable,$no_polis,$outstanding_balance,$reference_no,$payment_amount,$from_bank_account_id,$to_bank_account_id;
    public $is_readonly=false,$payment_date,$bank_charges,$description,$reference_date;
    public $add_pesertas=[],$no_peserta=[],$nama_peserta=[];
    public $titipan_premi,$temp_titipan_premi=[],$temp_arr_titipan_id=[],$total_titipan_premi=0;
    protected $listeners = ['emit-add-bank'=>'emitAddBank','set-titipan-premi'=>'setTitipanPremi'];
    public function render()
    {
        return view('livewire.income-recovery-refund.insert');
    }
    
    public function mount()
    {
        $this->no_voucher = generate_no_voucher_income();
        $this->payment_date = date('Y-m-d');
        $this->reference_date = date('Y-m-d');
    }
    
    public function emitAddBank($id)
    {
        $this->from_bank_account_id = $id;
        $this->emit('init-form');
    }
    
    public function updated($propertyName)
    {
        if($propertyName=='no_polis'){
            $this->data = \App\Models\Policy::find($this->no_polis);
        }
        $this->emit('init-form');
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

    public function delete_peserta($key)
    {
        unset($this->add_pesertas[$key],$this->no_peserta[$key],$this->nama_peserta[$key]);
    }

    public function add_peserta()
    {
        $this->add_pesertas[] = count($this->add_pesertas);
        $this->no_peserta[] = '';
        $this->nama_peserta[] = '';
    }

    public function save()
    {
        $this->validate([
            'type' => 'required',
            'payment_amount' => 'required'
        ]);
        $this->payment_amount = replace_idr($this->payment_amount);
        $this->bank_charges = replace_idr($this->bank_charges);
        $data = new Income();
        $data->no_voucher = $this->no_voucher;
        $data->reference_type = 'Recovery Refund';
        $data->description = $this->description;
        $data->outstanding_balance = $this->outstanding_balance;
        $data->reference_no = $this->reference_no;
        $data->client = isset($this->data->no_polis) ? $this->data->no_polis .' / '. $this->data->pemegang_polis : '';
        $data->rekening_bank_id = $this->to_bank_account_id;
        $data->from_bank_account_id = $this->from_bank_account_id;
        $data->reference_date = $this->reference_date;
        $data->status = 2;
        $data->user_id = \Auth::user()->id;
        $data->payment_amount = $this->payment_amount;
        $data->bank_charges = $this->bank_charges;
        $data->payment_date = $this->payment_date;
        $data->policy_id = $this->no_polis;
        $data->type = $this->type;
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
                        'transaction_type'=>'Recovery Refund'
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
                        'transaction_type'=>'Recovery Refund'
                    ]);
                    $item->outstanding_balance = $item->outstanding_balance - ($data->payment_amount - $total_titipan_premi);
                    $item->payment_amount = $item->payment_amount + ($data->payment_amount - $total_titipan_premi);
                }
                $item->save();
            }
        }

        if($this->add_pesertas){
            foreach($this->add_pesertas as $k=>$v){
                if(!empty($this->no_peserta[$k]) and !empty($this->nama_peserta[$k])){
                    $peserta = new IncomePeserta();
                    $peserta->income_id = $data->id;
                    $peserta->no_peserta = $this->no_peserta[$k];
                    $peserta->nama_peserta = $this->nama_peserta[$k];
                    $peserta->type = 1; // Recovery Refund
                    $peserta->policy_id = $this->data->id;
                    $peserta->save();
                }
            }
        }

        \LogActivity::add("Income - Recovery Refund Submit {$this->data->id}");

        session()->flash('message-success',__('Data saved successfully'));
        
        return redirect()->route('income.recovery-refund');
    }
}
