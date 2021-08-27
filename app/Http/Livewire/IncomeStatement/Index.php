<?php

namespace App\Http\Livewire\IncomeStatement;

use Livewire\Component;

class Index extends Component
{
    public $data;
    public function render()
    {
        return view('livewire.income-statement.index');
    }

    public function mount()
    {
        $this->data = \App\Models\Journal::groupBy('coa_id')->where('transaction_table','income')->get();
    }
}
