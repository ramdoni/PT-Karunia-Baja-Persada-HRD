<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;

class UnderwritingDetail extends Component
{
    public $data,$konven_underwriting_id,$coa_id,$debit,$kredit,$payment_date,$description;
    public $count_account=[],$total_debit=0,$total_kredit=0;
    public $no_voucher,$bank_account_id,$is_readonly=false,$is_disabled=false;
    public function render()
    {
        return view('livewire.konven.underwriting-detail');
    }

    public function mount($id)
    {
        $this->data = \App\Models\KonvenUnderwriting::find($id);
        $this->no_voucher = $this->data->no_voucher;
        $this->bank_account_id = $this->data->bank_account_id;

        if($this->data->status==3) $this->is_readonly=true;
        foreach($this->data->coa as $k => $item){
            $this->count_account[$k] = $item->id; 
            $this->konven_underwriting_id[$k] = $item->id;
            $this->coa_id[$k] = $item->coa_id;
            $this->description[$k] = $item->description;
            $this->payment_date[$k] = $item->payment_date;
            $this->debit[$k] = format_idr($item->debit);
            $this->kredit[$k] = format_idr($item->kredit);
            $this->total_debit += $item->debit;
            $this->total_kredit += $item->kredit;
        }
        $this->sumDebit();
        $this->sumKredit();
    }

    public function setOrdering($id,$ordering_before,$ordering_after)
    {
        $check_ordering = \App\Models\KonvenUnderwritingCoa::find($id);
        $check_child = \App\Models\KonvenUnderwritingCoa::where('konven_underwriting_id',$check_ordering->konven_underwriting_id)->where('ordering',$ordering_after)->first();
        if($check_child){
            $check_child->ordering = $ordering_before;
            $check_child->save();
        }
        $check_ordering->ordering = $ordering_after;
        $check_ordering->save();

        $this->mount($this->data->id);
        $this->emit('listenChangeForm');
    }

    public function addAccountForm()
    {
        $this->count_account[] = count($this->count_account);
        $this->coa_id[] = "";
        $this->description[] = "";
        $this->kredit[] = 0;
        $this->debit[] = 0;
        $this->payment_date[] = 0;
        $this->konven_underwriting_id[] = '';

        $this->emit('listenChangeForm');
    }

    public function autoSave()
    {
        $this->data->bank_account_id = $this->bank_account_id;
        $this->data->save();

        foreach($this->count_account as $k => $i){
            if(isset($this->konven_underwriting_id[$k]) and !empty($this->konven_underwriting_id[$k])){
                $item  = \App\Models\KonvenUnderwritingCoa::find($this->konven_underwriting_id[$k]);
            }else{
                $item  = new \App\Models\KonvenUnderwritingCoa();
                $item->konven_underwriting_id = $this->data->id;
                $item->ordering = \App\Models\KonvenUnderwritingCoa::where('konven_underwriting_id',$this->data->id)->count()+1;
            }

            $item->coa_id = $this->coa_id[$k];
            $item->kredit = replace_idr($this->kredit[$k]);
            $item->debit = replace_idr($this->debit[$k]);
            if($this->payment_date[$k]!="")
                $item->payment_date = date('Y-m-d',strtotime($this->payment_date[$k]));
            $item->description = $this->description[$k];
            $item->save();
        }
    }

    public function deleteAccountForm($key)
    {
        // delete database
        if(isset($this->konven_underwriting_id[$key]) and $this->konven_underwriting_id[$key]!="") \App\Models\KonvenUnderwritingCoa::find($this->konven_underwriting_id[$key])->delete();
        
        unset($this->count_account[$key]);
        unset($this->coa_id[$key]);
        unset($this->description[$key]);
        unset($this->kredit[$key]);
        unset($this->debit[$key]);
        unset($this->payment_date[$key]);
        unset($this->konven_underwriting_id[$key]);
        
        $this->sumDebit();
        $this->sumKredit();
    }

    public function sumDebit()
    {
        if(!$this->debit) return false;

        $this->total_debit=0;
        foreach($this->debit as $i){
            $this->total_debit += replace_idr($i);
        }
        if($this->total_debit != $this->total_kredit) 
            $this->is_disabled=true;
        else
            $this->is_disabled=false;
    }

    public function sumKredit() 
    {
        $this->total_kredit=0;
        foreach($this->kredit as $i){
            $this->total_kredit += replace_idr($i);
        }
        if($this->total_debit != $this->total_kredit) 
            $this->is_disabled=true;
        else
            $this->is_disabled=false;
    }

    public function save()
    {
        if($this->is_readonly) return false;
        $this->data->status=2;
        $this->data->bank_account_id = $this->bank_account_id;
        $this->data->save();

        foreach($this->data->coa as $k => $item){
            $item->coa_id = $this->coa_id[$k];
            $item->kredit = replace_idr($this->kredit[$k]);
            $item->debit = replace_idr($this->debit[$k]);
            $item->payment_date = date('Y-m-d',strtotime($this->payment_date[$k]));
            $item->description = $this->description[$k];
            $item->save();
        }
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->to('konven');
    }

    public function saveToJournal()
    {
        $this->validate([
            'bank_account_id'=>'required'
        ]);
        if($this->is_readonly) return false;
        $this->data->status = 3;
        $this->data->date_journal = date('Y-m-d');
        $this->data->bank_account_id = $this->bank_account_id;
        $this->data->save();

        foreach($this->data->coa as $k => $item){
            $item->coa_id = $this->coa_id[$k];
            $item->kredit = replace_idr($this->kredit[$k]);
            $item->debit = replace_idr($this->debit[$k]);
            $item->payment_date = date('Y-m-d',strtotime($this->payment_date[$k]));
            $item->description = $this->description[$k];
            $item->save();
        }
        
        foreach($this->data->coaDesc as $k => $item){
            $data  = new \App\Models\Journal();
            $data->transaction_id = $this->data->id;
            $data->transaction_table = 'konven_underwriting'; 
            $data->coa_id = $item->coa_id;
            $data->no_voucher = $this->no_voucher;
            $data->date_journal = date('Y-m-d');//date('Y-m-d',strtotime($this->payment_date[$k]));
            $data->debit = replace_idr(isset($this->debit[$k])?$this->debit[$k]:0);
            $data->kredit = replace_idr(isset($this->kredit[$k])?$this->kredit[$k]:0);
            $data->description = isset($this->description[$k])?$this->description[$k]:'';
            $data->bank_account_id = $this->bank_account_id;
            $data->saldo = replace_idr($this->debit[$k]!=0 ? $this->debit[$k] : ($this->kredit[$k]!=0?$this->kredit[$k] : 0));
            $data->save();
        }

        session()->flash('message-success','Submit to Journal success !');
            
        return redirect()->to('konven');
    }
}
