
<div class="body">
    <div class="mb-2 row">
        <div class="col-md-2">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="reas">
                <option value=""> --- Type --- </option>
                <option>Reas </option>
                <option>Ajri</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="type">
                <option value=""> --- Unit --- </option>
                <option value="1">[K] Konven </option>
                <option value="2">[S] Syariah</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="status">
                <option value=""> --- Status --- </option>
                <option value="1"> Unpaid </option>
                <option value="2"> Paid</option>
                <option value="5"> Draft</option>
            </select>
        </div>
        <div class="col-md-4">
            <a href="{{route('endorsement.dn-insert-reas')}}" class="btn btn-success"><i class="fa fa-plus"></i> Endorsement Reas</a>
            <a href="javascript:;" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
            <span wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover m-b-0 c_list">
            <thead>
                <tr>
                    <th>No</th>                                    
                    <th>Type</th>
                    <th>Status</th>                                    
                    <th>No Voucher</th>                                    
                    <th>Payment Date</th>                                    
                    <th>Voucher Date</th>  
                    <th>Debit Note / Kwitansi</th>
                    <th>Policy Number / Policy Holder</th>                    
                    <th>Description</th>                    
                    <th>Total</th>                                               
                    <th>From Bank Account</th>
                    <th>To Bank Account</th>
                    <th>Payment Amount</th>
                    <th>Bank Charges</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $k => $item)
                <tr>
                    <td style="width: 50px;">{{$k+1}}</td>
                    <td>
                        @if($item->reference_type=='Endorsement DN Reas')
                        Reas
                        @else
                        Ajri
                        @endif
                    </td>
                    <td>
                        @if($item->reference_type=='Endorsement DN Reas')
                            <a href="{{route('endorsement.dn-detail-reas',['id'=>$item->id])}}">{!!status_income($item->status)!!}</a>
                        @else
                            <a href="{{route('endorsement.dn-detail',['id'=>$item->id])}}">{!!status_income($item->status)!!}</a>
                        @endif
                        @if($item->status==5)
                        <a href="javascript:;" class="text-danger" wire:click="delete({{$item->id}})"><i class="fa fa-trash"></i></a>
                        @endif
                    </td>
                    <td>
                        @if($item->reference_type=='Endorsement DN Reas')
                        <a href="{{route('endorsement.dn-detail-reas',['id'=>$item->id])}}">{!!no_voucher($item)!!}</a>
                        @else
                        <a href="{{route('endorsement.dn-detail',['id'=>$item->id])}}">{!!no_voucher($item)!!}</a>
                        @endif
                    </td>
                    <td>{{$item->payment_date?date('d M Y', strtotime($item->payment_date)):'-'}}</td>
                    <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                    <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                    <td>{{$item->client ? $item->client : '-'}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{isset($item->nominal) ? format_idr($item->nominal) : '-'}}</td>
                    <td>{{isset($item->from_bank_account->no_rekening) ? $item->from_bank_account->no_rekening .' - '.$item->from_bank_account->bank.' an '.$item->from_bank_account->owner : '-'}}</td>
                    <td>{{isset($item->bank_account->no_rekening) ? $item->bank_account->no_rekening .' - '.$item->bank_account->bank.' an '.$item->bank_account->owner : '-'}}</td>
                    <td>{{isset($item->payment_amount) ? format_idr($item->payment_amount) : '-'}}</td>
                    <td>{{isset($item->bank_charges) ? format_idr($item->bank_charges) : '-'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br />
    {{$data->links()}}
    @push('after-scripts')
    <script>
        $('.total_debit_note').html("{{$total_payment_amount}}");
    </script>
    @endpush
</div>