<div class="mt-2" id="keydown">
    <div class="row">
        <div class="col-md-3">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..."/>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="status">
                <option value=""> --- Status --- </option>
                <option value="0">Draft</option>
                <option value="1">Sync</option>
                <option value="2">Invalid</option>
            </select>
        </div>
        <div class="col-md-3">
            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_upload_cancel" class="mb-2 btn btn-info" style="width:150px;"><i class="fa fa-upload"></i> Upload</a>
            @if($total_sync>0)
            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_confirm_sync_cancel" class="mb-2 btn btn-warning"><i class="fa fa-refresh"></i> Sync {{$total_sync?"(".$total_sync.")" : "(0)"}}</a>
            @endif
            <span wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
        </div>
        <div class="col-md-4 text-right">
            <h6>Sync : <span class="text-info">{{format_idr(\App\Models\SyariahCancel::where('status',1)->count())}}</span>, Draft : <span class="text-warning">{{format_idr(\App\Models\SyariahCancel::where('status',0)->count())}}</span>, Invalid : <span class="text-danger">{{format_idr(\App\Models\SyariahCancel::where('status',2)->count())}}</span>, Total : <span class="text-success">{{format_idr($data->total())}}</span></h6>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
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
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_cancel') as $column)
                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id','type_transaksi']))@continue @endif
                    <td>{{ in_array($column,['manfaat_sebelum_endors','dana_tab_baru_sebelum_endors','dana_ujrah_sebelum_endors','kontribusi_cancel','extra_kontribusi','jumlah_discount','handling_fee','jumlah_fee','jumlah_pph','jumlah_ppn','biaya_polis','biaya_sertifikat','ext_biaya_sertifikat','rp_biaya_sertifikat','ext_pst_sertifikat','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','net_sebelum_endors','extra_kontribusi_2','jumlah_discount_2','handling_fee_2','jumlah_fee_2','jumlah_pph_2','jumlah_pph_2','biaya_polis_2','biaya_sertifikat_2','net_setelah_endors','dengan_tagihan_atau_refund_premi']) ? format_idr($item->$column) : $item->$column }}</td>
                    @endforeach
                </tr>
                @php($num++)
                @endforeach
            </tbody>
        </table>
        <br />
        {{$data->links()}}
    </div>
    <div wire:ignore.self class="modal fade" id="modal_upload_cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <livewire:syariah.cancel-upload>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_confirm_sync_cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <livewire:syariah.cancel-sync>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_check_data_cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:90%;" role="document">
            <div class="modal-content">
                <livewire:syariah.cancel-check-data>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script>
    Livewire.on('refresh-page',()=>{
        $("#modal_upload_cancel").modal('hide');
        $("#modal_check_data_cancel").modal('hide');
    });
    Livewire.on('emit-check-data-cancel',()=>{
        $("#modal_upload_cancel").modal("hide");
        setTimeout(function(){
            $("#modal_check_data_cancel").modal(
                {
                    backdrop: 'static',
                    keyboard: false
                });
        },1000);
    });
</script>
@endpush