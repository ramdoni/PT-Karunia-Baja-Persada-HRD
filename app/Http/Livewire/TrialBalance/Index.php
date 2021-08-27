<?php

namespace App\Http\Livewire\TrialBalance;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    protected $data;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Journal::paginate(20);
        
        return view('livewire.trial-balance.index')->with(['data'=>$data]);
    }

    public function mount()
    {
    }
}
