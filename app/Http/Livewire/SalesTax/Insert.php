<?php

namespace App\Http\Livewire\SalesTax;

use Livewire\Component;

class Insert extends Component
{
    public $code,$description,$percen;
    public function render()
    {
        return view('livewire.sales-tax.insert');
    }

    public function save()
    {
        $this->validate([
            'code'=>'required',
            'description'=>'required',
            'percen'=>'required'
        ]);

        $data = new \App\Models\SalesTax();
        $data->code = $this->code;
        $data->description = $this->description;
        $data->percen = $this->percen;
        $data->save();

        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('sales-tax');
    }
}
