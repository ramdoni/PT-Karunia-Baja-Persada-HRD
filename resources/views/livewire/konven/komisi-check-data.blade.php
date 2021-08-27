<div class="body">
    <div class="row">
        <div class="px-2 pt-2">
            <h6>Double Data {{format_idr($data->total())}}</h6>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
            </div>
        </div>
        <div>
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="$emit('confirm-replace-all-komisi')"><i class="fa fa-refresh"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="$emit('confirm-keep-all-komisi')"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="$emit('confirm-delete-all-komisi')"><i class="fa fa-trash"></i> Delete All</a>
            <div wire:loading>
                <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="table-responsive pt-0">
        <table class="table table-striped m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th></th>
                    <th>User</th>
                    <th>Tgl Memo</th>
                    <th>No Reg</th>
                    <th>No Polis</th>
                    <th>No Polis Sistem</th>
                    <th>Pemegang Polis</th>
                    <th>Produk</th>
                    <th>Tgl Invoice</th>
                    <th>No Kwitansi</th>
                    <th>Total Peserta</th>
                    <th>No Peserta</th>
                    <th>Total UP</th>
                    <th>Total Premi Gross</th>
                    <th>EM</th>
                    <th>Disc/Pot Langsung</th>
                    <th>Premi Netto/yg dibayarkan</th>
                    <th>Perkalian Biaya Penutupan</th>
                    <th>Fee Base</th>
                    <th>Biaya Fee Base</th>
                    <th>Maintenance</th>
                    <th>Biaya Maintenance</th>
                    <th>Admin Agency</th>
                    <th>Biaya Admin Agency</th>
                    <th>Agen Penutup</th>
                    <th>Biaya Agen Penutup</th>
                    <th>Operasional Agency</th>
                    <th>Biaya Operasional Agency</th>
                    <th>Handling Fee Broker</th>
                    <th>Biaya Handling Fee</th>
                    <th>Referal Fee</th>
                    <th>Biaya RF</th>
                    <th>PPH</th>
                    <th>Jumlah PPH</th>
                    <th>PPN</th>
                    <th>Jumlah PPN</th>
                    <th>Cadangan Klaim</th>
                    <th>Jml Cadangan Klaim</th>
                    <th>Klaim Kemation</th>
                    <th>Pembatalan</th>
                    <th>Total Komisi</th>
                    <th>Tujuan Pembayaran</th>
                    <th>No Rekening</th>
                    <th>Tgl Lunas</th>
                </tr>
            </thead>
            <tbody>
                @php($num=$data->firstItem())
                @foreach($data as $item)
                <tr>
                    <td rowspan="2">{{$num}}</td>
                    <td>
                        <a href="javascript:;" wire:click="$emit('replace-confirm-komisi',{{$item->id}})" class="text-info"><i class="fa fa-refresh"></i></a>
                        <a href="javascript:;" wire:click="$emit('delete-confirm-komisi',{{$item->id}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                        <a href="javascript:;" wire:click="$emit('keep-confirm-komisi',{{$item->id}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                    </td>
                    <td>
                        <span class="badge badge-success">*New</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('konven_komisi') as $column)
                    @if($column=='id' || $column=='status' || $column=='created_at'||$column=='updated_at') @continue @endif
                    <td>{{$item->$column}}</td>
                    @endforeach
                </tr>
                @if($item->parent)
                <tr>
                    <td>
                    </td>
                    <td>
                        <span class="badge badge-warning">*Old</span>
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('konven_komisi') as $column)
                    @if($column=='id' || $column=='status' || $column=='created_at'||$column=='updated_at') @continue @endif
                    <td>{{$item->$column}}</td>
                    @endforeach
                </tr>
                @endif
                @php($num++)
                <tr><td colspan="48" style="background: #eee;padding:0;"></td></tr>
                @endforeach
            </tbody>
        </table>
        <br />
        {{$data->links()}}
    </div>
</div>
@push('after-scripts')
<script>
Livewire.on('confirm-replace-all-komisi',()=>{
    if(confirm('Replace all data ?')){
        Livewire.emit('replace-all-komisi');
    }
});
Livewire.on('confirm-delete-all-komisi',()=>{
    if(confirm('Delete all data ?')){
        Livewire.emit('delete-all-komisi');
    }
});
Livewire.on('confirm-keep-all-komisi',()=>{
    if(confirm('Keep all data ?')){
        Livewire.emit('keep-all-komisi');
    }
});
Livewire.on('keep-confirm-komisi',(id)=>{
    if(confirm('Keep this data ?')){
        Livewire.emit('keep-komisi',id);
    }
});
Livewire.on('delete-confirm-komisi',(id)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete-komisi',id);
    }
});
Livewire.on('replace-confirm-komisi',(id)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace-komisi',id);
    }
});
</script>
@endpush