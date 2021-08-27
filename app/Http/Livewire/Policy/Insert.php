<?php

namespace App\Http\Livewire\Policy;

use Livewire\Component;

class Insert extends Component
{
    public $no_polis,$pemegang_polis,$alamat,$cabang,$produk;
    public function render()
    {
        return view('livewire.policy.insert');
    }
    public function save()
    {
        $this->validate([
            'no_polis'=>'required',
            'pemegang_polis'=>'required',
            'alamat'=>'required',
            'cabang'=>'required',
            'produk'=>'required'
        ]);

        $data = new \App\Models\Policy();
        $data->no_polis = $this->no_polis;
        $data->pemegang_polis = $this->pemegang_polis;
        $data->alamat = $this->alamat;
        $data->cabang = $this->cabang;
        $data->produk = $this->produk;
        $data->save();

        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->to('policy');
    }
}