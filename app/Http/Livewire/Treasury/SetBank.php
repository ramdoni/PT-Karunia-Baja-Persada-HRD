<?php

namespace App\Http\Livewire\Treasury;

use Livewire\Component;

class SetBank extends Component
{
    public $selected_id,$bank_account_id,$transaction_date;
    protected $listeners = ['modalSetBank'];
    public function render()
    {
        return view('livewire.treasury.set-bank');
    }

    public function modalSetBank($id)
    {
        $this->selected_id = $id;
    }

    public function save()
    {
        $this->validate([
            'bank_account_id' => 'required',
            'transaction_date' => 'required'
        ]);

        $data = \App\Models\Journal::find($this->selected_id);
        $data->bank_account_id = $this->bank_account_id;
        $data->transaction_date = $this->transaction_date;
        $data->save();
        $this->reset(['bank_account_id','transaction_date','selected_id']);
        $this->emit('hideModalSetBank');
    }
}
