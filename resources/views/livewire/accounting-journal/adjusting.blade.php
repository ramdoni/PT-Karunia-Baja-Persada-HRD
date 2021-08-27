<div class="mx-2">
    <button type="button" data-toggle="modal" data-target="#modal_adjusting" class="btn btn-info"><i class="fa fa-edit"></i> Adjusting</button>
    <div wire:loading>
        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>

    <div wire:ignore.self class="modal fade" id="modal_adjusting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width:90%;" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="submit">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Adjusting {{$data->no_voucher}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-1">
                                <select class="form-control" wire:model="count_expand">
                                    @for($count=1;$count<=25;$count++)
                                        <option value="{{$count}}">{{$count}} Bulan</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div wire:loading>
                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            @for($loop_=0;$loop_<$count_expand;$loop_++)
                            <table class="table pl-0 mb-0 table-bordered">
                                <thead>
                                    <tr style="background: #eee;">
                                        <th>No Voucher</th>
                                        <th>Journal Date</th>
                                        <th>Account</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coas as $item)
                                    <tr>
                                        <td>{{$item->no_voucher}}</td>
                                        <td>{{date('d-M-Y',strtotime("+{$loop_} months"))}}</td>
                                        <td>{{$item->coa->name}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{$item->debit ? format_idr($item->debit/$count_expand) : 0}}</td>
                                        <td>{{$item->kredit ? format_idr($item->kredit/$count_expand) : 0}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br />
                            @endfor
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
