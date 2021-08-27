@section('title', 'Reinsurance')
@section('parentPageTitle', 'Syariah')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mt-2">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..."/>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" wire:model="status">
                                <option value=""> --- Status --- </option>
                                <option value="1">Draft</option>
                                <option value="2">Sync</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"  data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_upload_reinsurance" class="mb-2 btn btn-info" style="width:150px;"><i class="fa fa-upload"></i> Upload</a>
                            @if($total_sync>0)
                            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_confirm_sync_reinsurance" class="mb-2 btn btn-warning"><i class="fa fa-refresh"></i> Sync {{$total_sync?"(".$total_sync.")" : "(0)"}}</a>
                            @endif
                            <span wire:loading>
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                        </div>
                        <div class="col-md-3 text-right">
                            <h6>Sync : <span class="text-info">{{format_idr(\App\Models\SyariahReinsurance::where(['is_temp'=>0,'status'=>2])->count())}}</span>, Draft : <span class="text-warning">{{format_idr(\App\Models\SyariahReinsurance::where(['is_temp'=>0,'status'=>1])->count())}}</span>, Total : <span class="text-success">{{format_idr($data->total())}}</span></h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped m-b-0 table-hover c_list">
                            <thead>
                                <tr>
                                    <th>No</th>               
                                    <th>Status</th>                  
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
                                    <td>{{$num}}</td>
                                    <td>
                                        @if($item->status==1)
                                        <span class="badge text-warning">Draft</span>
                                        <a href="javascript:;" class="text-danger" wire:click="delete({{$item}})"><i class="fa fa-trash"></i></a>
                                        @endif
                                        @if($item->status==2)
                                        <span class="badge text-success">Sync</span>
                                        @endif
                                    </td>
                                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('syariah_reinsurance') as $column)
                                    @if(in_array($column,['created_at','updated_at','id','status','is_temp','parent_id','user_id']))@continue @endif
                                    <td>{{ in_array($column,['nilai_manfaat_asuransi_total','nilai_manfaat_asuransi_or','nilai_manfaat_asuransi_reas','kontribusi_ajri','kontribusi_reas_gross','ujroh','em','kontribusi_reas_netto']) ? format_idr($item->$column) : $item->$column }}</td>
                                    @endforeach
                                </tr>
                                @php($num++)
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {{$data->links()}}
                </div>
                <div class="modal fade" id="modal_confirm_sync_reinsurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <livewire:syariah.reinsurance-sync>
                        </div>
                    </div>
                </div>
                <div wire:ignore.self class="modal fade" id="modal_upload_reinsurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <livewire:syariah.reinsurance-upload>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal_check_data_reinsurance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="max-width:90%;" role="document">
                        <div class="modal-content">
                            <livewire:syariah.reinsurance-check-data>
                        </div>
                    </div>
                </div>
                @push('after-scripts')
                <script>
                    Livewire.on('refresh-page',()=>{
                        $("#modal_confirm_sync_reinsurance").modal('hide');
                        $("#modal_upload_reinsurance").modal('hide');
                        $("#modal_check_data_reinsurance").modal('hide');
                    });
                    Livewire.on('emit-check-data',()=>{
                        $("#modal_upload_reinsurance").modal("hide");
                        setTimeout(function(){
                            $("#modal_check_data_reinsurance").modal(
                                {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                        },1000);
                    });
                </script>
                @endpush
            </div>
        </div>
    </div>
</div>