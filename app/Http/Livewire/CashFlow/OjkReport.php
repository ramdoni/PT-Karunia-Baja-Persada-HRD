<?php

namespace App\Http\Livewire\CashFlow;

use Livewire\Component;

class OjkReport extends Component
{
    public $year;
    public function render()
    {
        return view('livewire.cash-flow.ojk-report');
    }

    public function mount()
    {
        $this->year = date('Y');
        \LogActivity::add("Cash Flow Ojk Report");
    }
}
