<?php

namespace App\Http\Livewire\AccountingJournal;

use Livewire\Component;

class SetCodeCashflowCheckbox extends Component
{
    public $code_cashflow_id,$value_multiple_cashflow;
    protected $listeners = ['modalSetCodeCashflowCheckbox'];
    public function render()
    {
        return view('livewire.accounting-journal.set-code-cashflow-checkbox');
    }
    public function mount()
    {
        \LogActivity::add("Accounting - Journal Set Code Cahs Flow Multiple");
    }
    public function modalSetCodeCashflowCheckbox($value_multiple_cashflow)
    {
        $this->value_multiple_cashflow = $value_multiple_cashflow;
    }
    public function save()
    {
        $this->validate([
            'code_cashflow_id'=>'required'
        ]);
        
        foreach($this->value_multiple_cashflow as $k => $id){
            if($id){
                $data = \App\Models\Journal::find($id);
                $data->code_cashflow_id = $this->code_cashflow_id;
                $data->save();
            }
        }

        $this->emit('modalSetCodeCashflowCheckboxHide');
        $this->value_multiple_cashflow = [];
        $this->code_cashflow_id = '';
        session()->flash('message-success',__('Data saved successfully'));
    }
}
