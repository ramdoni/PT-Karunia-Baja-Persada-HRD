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
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="$emit('confirm-replace-all-underwriting')"><i class="fa fa-refresh"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="$emit('confirm-keep-all-underwriting')"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="$emit('confirm-delete-all-underwriting')"><i class="fa fa-trash"></i> Delete All</a>
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
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_underwritings') as $column)
                    @if(in_array($column,['type_transaksi','created_at','updated_at','id','status','is_temp','parent_id','user_id']))@continue @endif
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
                        <a href="javascript:;" wire:click="$emit('replace-confirm-underwriting',{{$item->id}})" class="text-info"><i class="fa fa-refresh"></i></a>
                        <a href="javascript:;" wire:click="$emit('delete-confirm-underwriting',{{$item->id}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                        <a href="javascript:;" wire:click="$emit('keep-confirm-underwriting',{{$item->id}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                    </td>
                    <td>
                        <span class="badge badge-success">*New</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_underwritings') as $column)
                    @if(in_array($column,['type_transaksi','created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['manfaat_Kepesertaan_tertunda','kontribusi_kepesertaan_tertunda','jml_kepesertaan','nilai_manfaat','dana_tabbaru','dana_ujrah','kontribusi','ektra_kontribusi','total_kontribusi','pot_langsung','jumlah_diskon','handling_fee','jumlah_fee','jumlah_pph','jumlah_ppn','biaya_polis','biaya_sertifikat','extpst','net_kontribusi','pembayaran','piutang','pengeluaran_ujroh']) ? format_idr($item->$column) : $item->$column }}</td>
                    @endforeach
                </tr>
                @if($item->parent)
                <tr>
                    <td></td>
                    <td>
                        <span class="badge badge-warning">*Old</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_underwritings') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['manfaat_Kepesertaan_tertunda','kontribusi_kepesertaan_tertunda','jml_kepesertaan','nilai_manfaat','dana_tabbaru','dana_ujrah','kontribusi','ektra_kontribusi','total_kontribusi','pot_langsung','jumlah_diskon','handling_fee','jumlah_fee','jumlah_pph','jumlah_ppn','biaya_polis','biaya_sertifikat','extpst','net_kontribusi','pembayaran','piutang','pengeluaran_ujroh']) ? format_idr($item->parent->$column) : $item->parent->$column }}</td>
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
Livewire.on('confirm-replace-all-underwriting',()=>{
    if(confirm('Replace all data ?')){
        Livewire.emit('replace-all-underwriting');
    }
});
Livewire.on('confirm-delete-all-underwriting',()=>{
    if(confirm('Delete all data ?')){
        Livewire.emit('delete-all-underwriting');
    }
});
Livewire.on('confirm-keep-all-underwriting',()=>{
    if(confirm('Keep all data ?')){
        Livewire.emit('keep-all-underwriting');
    }
});
Livewire.on('keep-confirm-underwriting',(id)=>{
    if(confirm('Keep this data ?')){
        Livewire.emit('keep-underwriting',id);
    }
});
Livewire.on('delete-confirm-underwriting',(id)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete-underwriting',id);
    }
});
Livewire.on('replace-confirm-underwriting',(id)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace-underwriting',id);
    }
});
</script>
@endpush