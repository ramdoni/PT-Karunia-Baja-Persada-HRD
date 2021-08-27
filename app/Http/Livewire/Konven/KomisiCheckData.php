<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use Livewire\WithPagination;

class KomisiCheckData extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $keyword,$total_sync=0;
    protected $listeners = [
                            'emit-check-data-komisi'=>'$refresh',
                            'delete-all-komisi'=>'deleteAll',
                            'keep-all-komisi'=>'keepAll',
                            'replace-all-komisi'=>'replaceAll',
                            'replace-komisi'=>'replaceNew',
                            'delete-komisi'=>'deleteNew',
                            'keep-komisi'=>'keepNew',
                            'replace-all-komisi'=>'replaceAll',
                            'delete-old-komisi'=>'deleteOld'
                        ];
    public function render()
    {
        $data = \App\Models\KonvenKomisi::orderBy('id','DESC')->where('is_temp',1);
        if($this->keyword) $data = $data->where(function($table){
            $table->where('user', 'LIKE',"{$this->keyword}")
                ->orWhere('tgl_memo', 'LIKE',"{$this->keyword}")
                ->orWhere('no_reg', 'LIKE',"{$this->keyword}")
                ->orWhere('no_polis', 'LIKE',"{$this->keyword}")
                ->orWhere('no_polis_sistem', 'LIKE',"{$this->keyword}")
                ->orWhere('pemegang_polis', 'LIKE',"{$this->keyword}")
                ->orWhere('produk', 'LIKE',"{$this->keyword}")
                ->orWhere('tgl_invoice', 'LIKE',"{$this->keyword}")
                ->orWhere('no_kwitansi', 'LIKE',"{$this->keyword}")
                ->orWhere('total_peserta', 'LIKE',"{$this->keyword}")
                ->orWhere('no_peserta', 'LIKE',"{$this->keyword}")
                ->orWhere('total_up', 'LIKE',"{$this->keyword}")
                ->orWhere('total_premi_gross', 'LIKE',"{$this->keyword}")
                ->orWhere('em', 'LIKE',"{$this->keyword}")
                ->orWhere('disc_pot_langsung', 'LIKE',"{$this->keyword}")
                ->orWhere('premi_netto_yg_dibayarkan', 'LIKE',"{$this->keyword}")
                ->orWhere('perkalian_biaya_penutupan', 'LIKE',"{$this->keyword}")
                ->orWhere('fee_base', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_fee_base', 'LIKE',"{$this->keyword}")
                ->orWhere('maintenance', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_maintenance', 'LIKE',"{$this->keyword}")
                ->orWhere('admin_agency', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_admin_agency', 'LIKE',"{$this->keyword}")
                ->orWhere('agen_penutup', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_agen_penutup', 'LIKE',"{$this->keyword}")
                ->orWhere('operasional_agency', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_operasional_agency', 'LIKE',"{$this->keyword}")
                ->orWhere('handling_fee_broker', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_handling_fee_broker', 'LIKE',"{$this->keyword}")
                ->orWhere('referral_fee', 'LIKE',"{$this->keyword}")
                ->orWhere('biaya_rf', 'LIKE',"{$this->keyword}")
                ->orWhere('pph', 'LIKE',"{$this->keyword}")
                ->orWhere('jumlah_pph', 'LIKE',"{$this->keyword}")
                ->orWhere('ppn', 'LIKE',"{$this->keyword}")
                ->orWhere('jumlah_ppn', 'LIKE',"{$this->keyword}")
                ->orWhere('cadangan_klaim', 'LIKE',"{$this->keyword}")
                ->orWhere('jml_cadangan_klaim', 'LIKE',"{$this->keyword}")
                ->orWhere('klaim_kematian', 'LIKE',"{$this->keyword}")
                ->orWhere('pembatalan', 'LIKE',"{$this->keyword}")
                ->orWhere('total_komisi', 'LIKE',"{$this->keyword}")
                ->orWhere('tujuan_pembayaran', 'LIKE',"{$this->keyword}")
                ->orWhere('no_rekening', 'LIKE',"{$this->keyword}")
                ->orWhere('tgl_lunas', 'LIKE',"{$this->keyword}");   
        });
        return view('livewire.konven.komisi-check-data')->with(['data'=>$data->paginate(100)]);
    }
    public function keepNew($id)
    {
        \App\Models\KonvenKomisi::where('id',$id)->update(['is_temp'=>0]);
        if(\App\Models\KonvenKomisi::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
    public function deleteAll()
    {
        \App\Models\KonvenKomisi::where('is_temp',1)->delete();
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function keepAll()
    {
        \App\Models\KonvenKomisi::where('is_temp',1)->update(['is_temp'=>0]);
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function replaceAll()
    {
        $data = \App\Models\KonvenKomisi::where('is_temp',1)->get();
        foreach($data as $child){
            // delete parent
            \App\Models\KonvenKomisi::find($child->parent_id)->delete();
            // Check Expense
            $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_komisi','transaction_id'=>$child->parent_id])->first();
            if($expense and $expense->status==1) $expense->delete(); // delete expense
            $child->is_temp = 0;
            $child->parent_id = 0;
            $child->save();
        }
        session()->flash('message-success',__('Data saved successfully'));
        return redirect()->route('konven.underwriting');
    }
    public function deleteNew($id)
    {   
        \App\Models\KonvenKomisi::find($id)->delete();
        if(\App\Models\KonvenKomisi::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
    public function replaceNew($id)
    {
        $child = \App\Models\KonvenKomisi::where('id',$id)->first();
        if($child){
            // delete parent
            \App\Models\KonvenKomisi::find($child->parent_id)->delete();
            // Check Expense
            $expense = \App\Models\Expenses::where(['transaction_table'=>'konven_komisi','transaction_id'=>$child->parent_id])->first();
            if($expense and $expense->status==1) $expense->delete(); // delete expense
            $child->is_temp = 0;
            $child->parent_id = 0;
            $child->save();
        }

        if(\App\Models\KonvenKomisi::where('is_temp',1)->count()==0){
            session()->flash('message-success',__('Data saved successfully'));
            return redirect()->route('konven.underwriting');
        }
    }
}