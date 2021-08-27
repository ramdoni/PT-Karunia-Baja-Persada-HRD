<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;

class Cancel extends Component
{
    public $data,$income_id;
    protected $listeners = ['emit-cancel'=>'emitCancel'];
    public function render()
    {
        return view('livewire.income-premium-receivable.cancel');
    }
    public function emitCancel($id)
    {
        $this->income_id = $id;
        $this->data = \App\Models\Income::find($id);
    }
    public function cancel()
    {
        \App\Models\Income::where('id',$this->income_id)->update(['status'=>4]);
        \App\Models\KonvenUnderwriting::where(['id'=>$this->data->transaction_id])->update(['status'=>4]);

        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('income.premium-receivable');
    }
}
