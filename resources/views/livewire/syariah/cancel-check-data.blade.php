<div class="body">
    <div class="row">
        <div class="px-2 pt-2">
            <h6>Double Data <span class="text-danger">{{format_idr($data->total())}}</span></h6>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
            </div>
        </div>
        <div>
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="$emit('confirm-replace-all-cancel')"><i class="fa fa-refresh"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="$emit('confirm-keep-all-cancel')"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="$emit('confirm-delete-all-cancel')"><i class="fa fa-trash"></i> Delete All</a>
        </div>
        <div wire:loading>
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="table-responsive pt-0">
        <table class="table m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th></th>
                    <th></th>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_cancel') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id']))@continue @endif
                    <th>{{ucfirst($column)}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php($num=$data->firstItem())
                @foreach($data as $item)
                <tr>
                    <td rowspan="2">{{$num}}</td>
                    <td>
                        <a href="javascript:;" wire:click="$emit('replace-confirm-cancel',{{$item->id}})" class="text-info"><i class="fa fa-refresh"></i></a>
                        <a href="javascript:;" wire:click="$emit('delete-confirm-cancel',{{$item->id}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                        <a href="javascript:;" wire:click="$emit('keep-confirm-cancel',{{$item->id}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                    </td>
                    <td>
                        <span class="badge badge-success">*New</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_cancel') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['manfaat_sebelum_endors','dana_tab_baru_sebelum_endors','dana_ujrah_sebelum_endors','kontribusi_cancel','extra_kontribusi','jumlah_discount','handling_fee','jumlah_fee','jumlah_pph','jumlah_ppn','biaya_polis','biaya_sertifikat','ext_biaya_sertifikat','rp_biaya_sertifikat','ext_pst_sertifikat','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','extra_kontribusi_2','jumlah_discount_2','handling_fee_2','jumlah_fee_2','jumlah_pph_2','jumlah_pph_2','biaya_polis_2','biaya_sertifikat_2','net_setelah_endors','dengan_tagihan_atau_refund_premi']) ? format_idr($item->$column) : $item->$column }}</td>
                    @endforeach
                </tr>
                @if($item->parent)
                <tr>
                    <td></td>
                    <td>
                        <span class="badge badge-warning">*Old</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_cancel') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['manfaat_sebelum_endors','dana_tab_baru_sebelum_endors','dana_ujrah_sebelum_endors','kontribusi_cancel','extra_kontribusi','jumlah_discount','handling_fee','jumlah_fee','jumlah_pph','jumlah_ppn','biaya_polis','biaya_sertifikat','ext_biaya_sertifikat','rp_biaya_sertifikat','ext_pst_sertifikat','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','extra_kontribusi_2','jumlah_discount_2','handling_fee_2','jumlah_fee_2','jumlah_pph_2','jumlah_pph_2','biaya_polis_2','biaya_sertifikat_2','net_setelah_endors','dengan_tagihan_atau_refund_premi']) ? format_idr($item->$column) : $item->$column }}</td>
                    @endforeach
                </tr>
                @endif
                <tr><td colspan="126" style="background: #eee;padding:0;"></td></tr>
                @php($num++)
                @endforeach
            </tbody>
        </table>
        <br />
        {{$data->links()}}
    </div>
</div>
@push('after-scripts')
<script>
Livewire.on('confirm-replace-all-cancel',()=>{
    if(confirm('Replace all data ?')){
        Livewire.emit('replace-all-cancel');
    }
});
Livewire.on('confirm-delete-all-cancel',()=>{
    if(confirm('Delete all data ?')){
        Livewire.emit('delete-all-cancel');
    }
});
Livewire.on('confirm-keep-all-cancel',()=>{
    if(confirm('Keep all data ?')){
        Livewire.emit('keep-all-cancel');
    }
});
Livewire.on('keep-confirm-cancel',(id)=>{
    if(confirm('Keep this data ?')){
        Livewire.emit('keep-cancel',id);
    }
});
Livewire.on('delete-confirm-cancel',(id)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete-cancel',id);
    }
});
Livewire.on('replace-confirm-cancel',(id)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace-cancel',id);
    }
});
</script>
@endpush