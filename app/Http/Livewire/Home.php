<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        if(\Auth::user()->user_access_id ==2) $this->redirect('finance');// redirect to finance
        if(\Auth::user()->user_access_id ==4) $this->redirect('inhouse-transfer');// redirect to treasury
        // if(\Auth::user()->user_access_id ==3) $this->redirect('accounting-journal'); // redirect to accounting
        return view('livewire.home');
    }
}
