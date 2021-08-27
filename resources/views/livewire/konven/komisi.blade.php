<div class="mt-2" id="keydown">
    <div class="row">
        <div class="col-md-3">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..."/>
        </div>
        <div class="px-0 col-md-1">
            <select class="form-control" wire:model="status">
                <option value=""> --- Status --- </option>
                <option value="0">Draft</option>
                <option value="1">Sync</option>
                <option value="2">Invalid</option>
            </select>
        </div>
        <div class="col-md-4">
            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_upload_komisi" class="mb-2 btn btn-info btn-sm" style="width:150px;"><i class="fa fa-upload"></i> Upload</a>
            @if($total_sync>0)
            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_confirm_sync_komisi" class="mb-2 btn btn-warning btn-sm"><i class="fa fa-refresh"></i> Sync {{$total_sync?"(".$total_sync.")" : "(0)"}}</a>
            @endif
        </div>
        <div class="col-md-4 text-right">
            <h6>Sync : <span class="text-info">{{format_idr(\App\Models\KonvenKomisi::where('status',1)->count())}}</span>, Draft : <span class="text-warning">{{format_idr(\App\Models\KonvenKomisi::where('status',0)->count())}}</span>, Invalid : <span class="text-danger">{{format_idr(\App\Models\KonvenKomisi::where('status',2)->count())}}</span>, Total : <span class="text-success">{{format_idr($data->total())}}</span></h6>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
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
                    <td>{{$num}}</td>
                    <td>
                        @if($item->status==0)
                            <span class="badge badge-warning">Draft</span>
                        @elseif($item->status==1)
                            <span class="badge badge-success">Sync</span>
                        @elseif($item->status==2)
                            <span class="badge badge-danger">Invalid</span>
                        @endif
                    </td>
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('konven_komisi') as $column)
                    @if($column=='id' || $column=='status' || $column=='created_at'||$column=='updated_at') @continue @endif
                    <td>{{$item->$column}}</td>
                    @endforeach
                </tr>
                @php($num++)
                @endforeach
            </tbody>
        </table>
        <br />
        {{$data->links()}}
    </div>
    <div wire:ignore.self class="modal fade" id="modal_upload_komisi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <livewire:konven.komisi-upload>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_confirm_sync_komisi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <livewire:konven.komisi-sync>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_check_data_komisi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:90%;" role="document">
            <div class="modal-content">
                <livewire:konven.komisi-check-data>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script>
    Livewire.on('emit-check-data-komisi',()=>{
        $("#modal_upload_komisi").modal("hide");
        setTimeout(function(){
            $("#modal_check_data_komisi").modal(
                {
                    backdrop: 'static',
                    keyboard: false
                });
        },1000);
    });
</script>
@endpush