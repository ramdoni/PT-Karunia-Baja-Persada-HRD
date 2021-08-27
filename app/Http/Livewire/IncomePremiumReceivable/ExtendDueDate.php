<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;

class ExtendDueDate extends Component
{
    public $data,$days,$date;
    public function render()
    {
        return view('livewire.income-premium-receivable.extend-due-date');
    }
    public function updated($property)
    {
        if($property=='days') $this->date = date('Y-m-d', strtotime("{$this->data->due_date} {$this->days} days"));
        if($property=='date'){
            $start = new \DateTime($this->data->due_date); $end = new \DateTime($this->date);
            $difference = $start->diff($end);
            $this->days =  $difference->d;
        }
    }
    public function mount($data)
    {
        $this->data = $data;
        $this->date  = $data->due_date;
    }
    public function save()
    {
        $this->validate([
            'date'=>'required'
        ]);
        if($this->date != $this->data->due_date){
            \App\Models\Income::find($this->data->id)->update(['due_date'=>$this->date]);
            if($this->data->type==1) \App\Models\KonvenUnderwriting::find($this->data->transaction_id)->update(['extend_tgl_jatuh_tempo'=>$this->date]);
            if($this->data->type==2) \App\Models\SyariahUnderwriting::find($this->data->transaction_id)->update(['extend_tgl_jatuh_tempo'=>$this->date]);
        }
        
        $this->emit('message-success','Due date has been changed successfully');
        $this->emit('refresh-page');
    }
}
