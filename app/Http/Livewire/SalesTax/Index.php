<?php

namespace App\Http\Livewire\SalesTax;

use Livewire\Component;

class Index extends Component
{
    public $data;
    public function render()
    {
        return view('livewire.sales-tax.index');
    }

    public function mount()
    {
        $this->data = \App\Models\SalesTax::orderBy('id')->get();
    }

    public function delete($id)
    {
        \App\Models\SalesTax::find($id)->delete();
    }
}
