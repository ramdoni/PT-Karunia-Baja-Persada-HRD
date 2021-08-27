<?php

namespace App\Http\Livewire\AccountingJournal;

use Livewire\Component;
use App\Models\Journal;

class Insert extends Component
{
    public $no_voucher,$date_journal,$coa_id,$kredit,$debit,$description,$array_coa,$is_submit=false;
    public function render()
    {
        return view('livewire.accounting-journal.insert');
    }

    public function updated($propertyName)
    {
        $this->emit('init-form');
    }

    public function mount()
    {
        $this->date_journal = date('Y-m-d');
        $this->add_account();
    }

    public function delete($key)
    {
        unset($this->array_coa[$key],$this->coa_id[$key],$this->kredit[$key],$this->debit[$key],$this->description[$key]);
        $this->emit('init-form');
    }

    public function add_account()
    {
        $this->array_coa[] = $this->array_coa ? count($this->array_coa) : 0;
        $this->coa_id[] = '';$this->kredit[] = '';$this->debit[] = '';$this->description[] = '';
        $this->emit('init-form');
    }

    public function save()
    {
        $this->emit('init-form');
        $this->validate([
            'coa_id.*' => 'required'    
        ]);
        
        foreach($this->array_coa as $k => $i){
            if($k==0) $this->no_voucher = generate_no_voucher($this->coa_id[$k]);

            $data = new Journal();
            $data->no_voucher = $this->no_voucher;
            $data->coa_id = $this->coa_id[$k];
            $data->debit = $this->debit[$k];
            $data->kredit = $this->kredit[$k];
            $data->date_journal = $this->date_journal;
            $data->is_auto = 0;
            $data->save();
        }
        
        session()->flash('message-success',__('Journal submited'));
        
        \LogActivity::add("Add Journal {$this->no_voucher}");
        
        return redirect()->route('accounting-journal.index');
    }   
}
