<?php

namespace App\Http\Livewire\Vendor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vendor;

class Index extends Component
{
    public $keyword;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = Vendor::orderBy('id','DESC');

        if($this->keyword) $data = $data->where('name','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('email','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('phone','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('address','LIKE', '%'.$this->keyword.'%')
                                        ->orWhere('pic','LIKE', '%'.$this->keyword.'%');

        return view('livewire.vendor.index')->with(['data'=>$data->paginate(100)]);
    }
}
