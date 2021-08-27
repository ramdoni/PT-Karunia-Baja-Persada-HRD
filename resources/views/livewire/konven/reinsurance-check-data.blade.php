<div class="body">
    <div class="row">
        <div class="px-2 pt-2">
            <h6>Double Data : {{format_idr($data->total())}}</h6>
        </div>
        <div class="px-2 col-md-3">
            <div class="form-group">
                <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
            </div>
        </div>
        <div class="px-2">
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="$emit('confirm-replace-all')"><i class="fa fa-check"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="$emit('confirm-keep-all')"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="$emit('confirm-delete-all')"><i class="fa fa-trash"></i> Delete All</a>
            <div wire:loading>
                <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="table-responsive pt-0">
        <table class="table m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>No Polis</th>
                    <th>Pemegang Polis</th>
                    <th>Peserta</th>
                    <th>Uang Pertanggungan</th>
                    <th>Uang Pertanggungan Reas</th>
                    <th>Premi Gross Ajri</th>
                    <th>Premi Reas</th>
                    <th>Komisi Reansurance</th>
                    <th>Premi Reas Netto</th>
                    <th>Keterangan T/F</th>
                    <th>Kirim Reas</th>
                    <th>Broker Re / Reasuradur</th>
                    <th>Reasuradur</th>
                    <th>Bulan</th>
                    <th>Ekawarsa / Jangkawarsa</th>
                    <th>Produk</th>
                </tr>
            </thead>
            <tbody>
                @php($num=$data->firstItem())
                @foreach($data as $key => $item)
                    <tr>
                        <td>{{$num}}</td>
                        <td>
                            <a href="javascript:;" wire:click="$emit('replace-confirm',{{$item->id}})" class="text-info"><i class="fa fa-refresh"></i></a>
                            <a href="javascript:;" wire:click="$emit('delete-confirm',{{$item->id}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                            <a href="javascript:;" wire:click="$emit('keep-confirm',{{$item->id}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                        </td>
                        <td>
                            <span class="badge badge-success">*New</span>
                        </td>
                        <td>{{$item->no_polis}}</td>
                        <td>{{$item->pemegang_polis}}</td>
                        <td>{{$item->peserta}}</td>
                        <td>{{format_idr($item->uang_pertanggungan)}}</td>
                        <td>{{format_idr($item->uang_pertanggungan_reas)}}</td>
                        <td>{{format_idr($item->premi_gross_ajri)}}</td>
                        <td>{{format_idr($item->premi_reas)}}</td>
                        <td>{{format_idr($item->komisi_reinsurance)}}</td>
                        <td>{{format_idr($item->premi_reas_netto)}}</td>
                        <td>{{$item->keterangan}}</td>
                        <td>{{$item->kirim_reas}}</td>
                        <td>{{$item->broker_re}}</td>
                        <td>{{$item->reasuradur}}</td>
                        <td>{{$item->bulan}}</td>
                        <td>{{$item->ekawarsa_jangkawarsa}}</td>
                        <td>{{$item->produk}}</td>
                    </tr>
                    @if($item->parent)
                    <tr>
                        <td></td>
                        <td></td>
                        <td><span class="badge badge-warning">*Old</span></td>
                        <td>{{$item->parent->no_polis}}</td>
                        <td>{{$item->parent->pemegang_polis}}</td>
                        <td>{{$item->parent->peserta}}</td>
                        <td>{{format_idr($item->parent->uang_pertanggungan)}}</td>
                        <td>{{format_idr($item->parent->uang_pertanggungan_reas)}}</td>
                        <td>{{format_idr($item->parent->premi_gross_ajri)}}</td>
                        <td>{{format_idr($item->parent->premi_reas)}}</td>
                        <td>{{format_idr($item->parent->komisi_reinsurance)}}</td>
                        <td>{{format_idr($item->parent->premi_reas_netto)}}</td>
                        <td>{{$item->parent->keterangan}}</td>
                        <td>{{$item->parent->kirim_reas}}</td>
                        <td>{{$item->parent->broker_re}}</td>
                        <td>{{$item->parent->reasuradur}}</td>
                        <td>{{$item->parent->bulan}}</td>
                        <td>{{$item->parent->ekawarsa_jangkawarsa}}</td>
                        <td>{{$item->parent->produk}}</td>
                    </tr>
                    @endif
                    @php($num++)
                    <tr><td colspan="18" style="padding:0"></td></tr>
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
Livewire.on('keep-confirm',(id)=>{
    if(confirm('Keep this data ?')){
        Livewire.emit('keep',id);
    }
});
Livewire.on('delete-confirm',(id)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete',id);
    }
});
Livewire.on('replace-confirm',(id)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace',id);
    }
});
</script>
@endpush