<?php

namespace App\Http\Livewire\SalesTax;

use Livewire\Component;

class Edit extends Component
{
    public $data;
    public $code,$description,$percen;
    public function render()
    {
        return view('livewire.sales-tax.insert');
    }

    public function mount($id)
    {
        $this->data = \App\Models\SalesTax::find($id);
        $this->code = $this->data->code;
        $this->description = $this->data->description;
        $this->percen = $this->data->percen;
    }

    public function save()
    {
        $this->validate([
            'code'=>'required',
            'description'=>'required',
            'percen'=>'required'
        ]);

        $this->data->code = $this->code;
        $this->data->description = $this->description;
        $this->data->percen = $this->percen;
        $this->data->save();

        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('sales-tax');
    }
}
