<?php

namespace App\Http\Livewire\Operation;

use Livewire\Component;

class ExpenseInsert extends Component
{
    public $no_voucher,$recipient,$reference_type,$reference_no,$reference_date,$description,$nominal,$outstanding_balance,$tax_id,$payment_amount;
    public $tax_amount,$total_amount;
    public function render()
    {
        return view('livewire.operation.expense-insert');
    }

    public function mount()
    {
        $this->no_voucher = generate_no_voucher_expense();
    }

    public function calculate()
    {
        // get percen
        if($this->tax_id){
            $tax = \App\Models\SalesTax::find($this->tax_id);
            $this->tax_amount = format_idr($tax->percen * replace_idr($this->nominal) / 100);
        }

        $this->total_amount =  format_idr(replace_idr($this->nominal) + replace_idr($this->tax_amount));
         
        $this->outstanding_balance = format_idr(replace_idr($this->total_amount) - replace_idr($this->payment_amount));
    }

    public function save()
    {
        $this->payment_amount = replace_idr($this->payment_amount);
        $this->validate(
            [
                'recipient' => 'required',
                'reference_type' => 'required',
                'reference_no' => 'required',
                'reference_date' => 'required',
                'nominal' => 'required',
                'payment_amount' => 'required|numeric|max:'.replace_idr($this->total_amount),
            ],
            [
                'payment_amount.max' => 'The Payment Amount may not be greater than '. $this->total_amount
            ]
        );

        $data = new \App\Models\Expenses();
        $data->no_voucher = $this->no_voucher;
        $data->recipient = $this->recipient;
        $data->user_id = \Auth::user()->id;
        $data->reference_type = $this->reference_type;
        $data->reference_date = $this->reference_date;
        $data->description = $this->description;
        $data->tax_id = $this->tax_id;
        $data->nominal = replace_idr($this->nominal);
        $data->tax_amount = replace_idr($this->tax_amount);
        $data->outstanding_balance = replace_idr($this->outstanding_balance);
        $data->reference_no = $this->reference_no;
        $data->payment_amount = $this->payment_amount;
        $data->save();
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->to('operation');
    }
}
