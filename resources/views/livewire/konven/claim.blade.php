@section('title', 'Claim')
@section('parentPageTitle', 'Home')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mt-2">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..."/>
                        </div>
                        <div class="px-0 col-md-1">
                            <select class="form-control" wire:model="status">
                                <option value=""> --- Status --- </option>
                                <option value="1">Draft</option>
                                <option value="2">Outstanding</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a href="javascript:void(0)" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#modal_upload_claim" class="mb-2 btn btn-info btn-sm" style="width:150px;"><i class="fa fa-upload"></i> Upload</a>
                            @if($total_sync>0)
                            <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_confirm_sync" class="mb-2 btn btn-warning btn-sm"><i class="fa fa-refresh"></i> Sync {{$total_sync?"(".$total_sync.")" : "(0)"}}</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped m-b-0 table-hover c_list">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Sync</th>
                                    <th>No Polis</th>
                                    <th>Pemegang Polis</th>
                                    <th>Nomor Partisipan</th>
                                    <th>Nama Partisipan</th>
                                    <th>Nilai Klaim</th>
                                    <th>OR</th>
                                    <th>Reas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($num=$data->firstItem())
                                @foreach($data as $key => $item)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>
                                        @if($item->status_claim==1)
                                        <span class="badge badge-warning">Draft</span>
                                        @elseif($item->status_claim==2)
                                        <span class="badge badge-success">Sync</span>
                                        @else
                                        <span class="badge badge-danger">Invalid</span>
                                        @endif
                                    </td>
                                    <td>{{$item->nomor_polis}}</td>
                                    <td>{{$item->nama_pemegang}}</td>
                                    <td>{{$item->nomor_partisipan}}</td>
                                    <td>{{$item->nama_partisipan}}</td>
                                    <td>{{format_idr($item->nilai_klaim)}}</td>
                                    <td>{{format_idr($item->or)}}</td>
                                    <td>{{format_idr($item->reas)}}</td>
                                    <td>{{$item->status}}</td>
                                </tr>
                                @php($num++)
                                @endforeach
                            </tbody>
                        </table>
                        <br />
                        {{$data->links()}}
                    </div>
                    <div wire:ignore.self class="modal fade" id="modal_upload_claim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <livewire:konven.claim-upload>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_confirm_sync" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <livewire:konven.claim-sync>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>