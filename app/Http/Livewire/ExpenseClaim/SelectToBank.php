<?php

namespace App\Http\Livewire\ExpenseClaim;

use Livewire\Component;

class SelectToBank extends Component
{
    public $to_bank_account_id, $is_readonly = false;
    protected $listeners = ['emit-add-bank'=>'emitAddBank'];

    public function render()
    {
        return view('livewire.expense-claim.select-to-bank');
    }

    public function mount($is_readonly,$to_bank_account_id)
    {
        $this->is_readonly = $is_readonly;
        $this->to_bank_account_id = $to_bank_account_id;
    }
    
    public function emitAddBank($id)
    {
        $this->to_bank_account_id = $id;
    }
}
