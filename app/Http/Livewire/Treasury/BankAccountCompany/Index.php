<?php

namespace App\Http\Livewire\Treasury\BankAccountCompany;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$coa_group_id;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = \App\Models\BankAccount::orderBy('id','DESC')->where('is_client',0);
        if($this->keyword) $data = $data->where(function($table){
                                        $table->where('no_rekening','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('owner','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('bank','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('cabang','LIKE', '%'.$this->keyword.'%');
                                    });

        return view('livewire.treasury.bank-account-company.index')->with(['data'=>$data->paginate(50)]);
    }
    public function mount()
    {
        \LogActivity::add("Bank Account");
    }
    public function delete($id)
    {
        \LogActivity::add("Bank Account Delete {$id}");

        \App\Models\BankAccount::find($id)->delete();
    }
}
