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
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="$emit('confirm-replace-all')"><i class="fa fa-refresh"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="$emit('confirm-keep-all')"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="$emit('confirm-delete-all')"><i class="fa fa-trash"></i> Delete All</a>
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
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_reinsurance') as $column)
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
                        <a href="javascript:;" wire:click="$emit('replace-confirm',{{$item}})" class="text-info"><i class="fa fa-refresh"></i></a>
                        <a href="javascript:;" wire:click="$emit('delete-confirm',{{$item}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                        <a href="javascript:;" wire:click="$emit('keep-confirm',{{$item}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                    </td>
                    <td>
                        <span class="badge badge-success">*New</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_reinsurance') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['nilai_manfaat_asuransi_total','nilai_manfaat_asuransi_or','nilai_manfaat_asuransi_reas','kontribusi_ajri','kontribusi_reas_gross','ujroh','em','kontribusi_reas_netto']) ? format_idr($item->$column) : $item->$column }}</td>
                    @endforeach
                </tr>
                @if($item->parent)
                <tr>
                    <td></td>
                    <td>
                        <span class="badge badge-warning">*Old</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_reinsurance') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['nilai_manfaat_asuransi_total','nilai_manfaat_asuransi_or','nilai_manfaat_asuransi_reas','kontribusi_ajri','kontribusi_reas_gross','ujroh','em','kontribusi_reas_netto']) ? format_idr($item->$column) : $item->$column }}</td>
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
Livewire.on('confirm-replace-all',()=>{
    if(confirm('Replace all data ?')){
        Livewire.emit('replace-all');
    }
});
Livewire.on('confirm-delete-all',()=>{
    if(confirm('Delete all data ?')){
        Livewire.emit('delete-all');
    }
});
Livewire.on('confirm-keep-all',()=>{
    if(confirm('Keep all data ?')){
        Livewire.emit('keep-all');
    }
});
Livewire.on('keep-confirm',(data)=>{
    if(confirm('Keep this data ?')){
        Livewire.emit('keep',data);
    }
});
Livewire.on('delete-confirm',(data)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete',id);
    }
});
Livewire.on('replace-confirm',(data)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace',data);
    }
});
</script>
@endpush