<?php

namespace App\Http\Livewire\CashFlow;

use Livewire\Component;

class Index extends Component
{
    public $data;
    public function render()
    {
        return view('livewire.cash-flow.index');
    }

    public function mount()
    {
        $this->data = \App\Models\Journal::groupBy('coa_id')->get();
        \LogActivity::add("Cash Flow");
    }
}
