<?php

namespace App\Http\Livewire\Treasury;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$year,$month,$coa_id,$id_active,$code_cashflow_id,$data_temp;
    protected $listeners = ['hideModalSetBank','hideModalSetBankMultiple'];
    public $set_multiple_bank=false,$value_multiple_bank=[],$check_all=false;
    public function render()
    {
        $data = \App\Models\Journal::orderBy('id','DESC');
        if($this->keyword) $data = $data->where('no_voucher','LIKE',"%{$this->keyword}%")->orWhere('transaction_number','LIKE',"%{$this->keyword}%");
        if($this->year) $data = $data->whereYear('date_journal',$this->year);
        if($this->month) $data = $data->whereMonth('date_journal',$this->month);
        if($this->coa_id) $data = $data->where('coa_id',$this->coa_id);
        if($this->code_cashflow_id) $data = $data->where('code_cashflow_id',$this->code_cashflow_id);
        $temp = clone $data;
        foreach($temp->whereNull('bank_account_id')->paginate(100) as $k => $i){
            $this->data_temp[$k] = $i;
        }
        return view('livewire.treasury.index')->with(['data'=>$data->paginate(100)]);
    }
    public function hideModalSetBankMultiple(){
        $this->reset(['value_multiple_bank']);
    }
    public function submitBank()
    {
        if(count($this->value_multiple_bank)==0){
            $this->emit('message',__("Select Transaction First !"));
        }else $this->emit('modalSetBankMultiple',$this->value_multiple_bank);
    }
    public function setBank($id)
    {
        $this->emit('modalSetBank',$id);
    }
    public function checkAll()
    {
        if($this->check_all){
            foreach($this->data_temp as $k => $item){
                $this->value_multiple_bank[$k] = $item['id'];
            }
        }else $this->value_multiple_bank=[];
    }
}