<?php

namespace App\Http\Livewire\AccountingJournal;

use Livewire\Component;
use \App\Models\JournalReclas;
use \App\Models\Journal;
use \App\Models\KonvenUnderwriting;

class Detail extends Component
{
    public $data,$total_amount,$payment_amount,$count_account=[],$is_readonly=false,$sum_debit,$sum_kredit,$history_reclass;
    public $is_submit_journal,$coas,$is_reclass=false;
    public $uw,$journal_date;
    public $coa_id,$description_coa,$debit,$kredit,$total_debit,$total_kredit;
    public $is_otp_editable=false,$otp,$is_submit;
    protected $listeners = ['otp-editable'=>'otpEditable'];

    public function render()
    {
        return view('livewire.accounting-journal.detail');
    }

    public function otpEditable($otp)
    {
        $this->otp = $otp;
        $this->is_otp_editable = true;
    }

    public function saveJournalDate()
    {
        $this->validate([
            'journal_date'=>'required'
        ]); 

        Journal::where(['no_voucher'=>$this->data->no_voucher])->update(['date_journal'=> $this->journal_date]);
        
        \LogActivity::add("Accounting Editable Journal Date OTP {$this->data->id}");

        $this->emit('message-success',__('Journal Date saved.'));
        $this->is_otp_editable = false;
    }

    public function mount($id)
    {
        $this->data = Journal::find($id);
        $this->journal_date = date('Y-m-d',strtotime($this->data->date_journal));
        $this->coas = Journal::where('no_voucher', $this->data->no_voucher)->get();
        $this->sum_debit = Journal::where('no_voucher', $this->data->no_voucher)->sum('debit');
        $this->sum_kredit = Journal::where('no_voucher', $this->data->no_voucher)->sum('kredit');
        if($this->data->transaction_table=='konven_underwriting') $this->uw = KonvenUnderwriting::find($this->data->transaction_id);
        
        $this->history_reclass = JournalReclas::where('no_voucher',$this->data->no_voucher)->orderBy('id','DESC')->get();
        \LogActivity::add("Accounting - Journal Detail {$id}");
    }

    public function updated()
    {
        $this->total_debit=0;$this->total_kredit=0;
        foreach($this->count_account as $k => $v){
            $this->total_debit += replace_idr($this->debit[$k]);
            $this->total_kredit += replace_idr($this->kredit[$k]);
        }
        if($this->sum_kredit==$this->total_kredit and $this->sum_debit==$this->total_debit) $this->is_submit_journal=true;
        else $this->is_submit_journal = false;
        $this->emit('init-form');
    }

    public function cancel_reclass()
    {
        $this->is_reclass=false;
        $this->emit('changeForm');
        $this->reset(['count_account']);
    }
    
    public function reclass()
    {
        $this->is_reclass=true;
        $this->emit('init-form');
        $this->add_account();
    }

    public function add_account()
    {
        $this->count_account[] = count($this->count_account);
        $this->coa_id[] = '';$this->description_coa[] = '';$this->debit[] = 0;$this->kredit[] = 0;
        $this->emit('init-form');
    }

    public function delete($key)
    {
        unset($this->count_account[$key],$this->coa_id[$key],$this->description_coa[$key],$this->debit[$key],$this->coa_id[$key]);
        $this->emit('init-form');
    }

    public function save()
    {
        $this->validate([
           'total_kredit' => 'required:max:'.replace_idr($this->sum_kredit) 
        ]);
        // last voucher
        $last = JournalReclas::where('no_voucher',$this->data->no_voucher)->orderBy('last_ordering','DESC')->first();
        $last_ordering = 0;
        if($last) $last_ordering = $last->last_ordering;

        foreach($this->coas as $k => $item){
            $reclass = new JournalReclas();
            $reclass->no_voucher = $this->data->no_voucher;
            $reclass->coa_id = $item->coa_id;
            $reclass->description = $item->description;
            $reclass->debit = $item->debit;
            $reclass->kredit = $item->kredit;
            $reclass->user_id= \Auth::user()->id;
            $reclass->last_ordering = $last_ordering+1;
            $reclass->save();
            $item->delete();
        }

        foreach($this->count_account as $k=>$item){
            $new = new Journal();
            $new->date_journal = date('Y-m-d');
            $new->no_voucher = $this->data->no_voucher;
            $new->coa_id = $this->coa_id[$k];
            $new->description = $this->description_coa[$k];
            $new->debit = replace_idr($this->debit[$k]);
            $new->kredit = replace_idr($this->kredit[$k]);
            $new->saldo = empty($this->debit[$k]) ? replace_idr($this->debit[$k]) : replace_idr($this->kredit[$k]);
            $new->save();
            $last_id = $new->id;
        }

        \LogActivity::add("Accounting - Journal Reclass {$this->data->no_voucher}");
        
        session()->flash('message-success','Reclassification saved.');   

        return redirect()->route('accounting-journal.detail',$last_id);
    }
}