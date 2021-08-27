<div class="body">
    <div class="mb-2 row">
        <div class="col-md-3">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="unit">
                <option value=""> --- Unit --- </option>
                <option value="1"> Konven </option>
                <option value="2"> Syariah</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control payment_date_expense" placeholder="Payment Date" />
        </div>
        <div class="col-md-1">
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
                    <th>No Voucher</th>                                    
                    <th>Payment Date</th>                                    
                    <th>Voucher Date</th>                                    
                    <th>Reference Date</th>
                    <th>Debit Note / Kwitansi</th>
                    <th>Policy Number / Policy Holder</th>                    
                    <th>Total</th>                                               
                    <th>No Rekening</th>
                    <th>Outstanding Balance</th>
                    <th>Payment Amount</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $k => $item)
                <tr>
                    <td style="width: 50px;">{{$k+1}}</td>
                    <td>{{$item->reference_type}}</td>
                    <td>{!!no_voucher($item)!!}</td>
                    <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                    <td>{{date('d M Y', strtotime($item->payment_date))}}</td>
                    <td>{{date('d M Y', strtotime($item->reference_date))}}</td>
                    <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                    <td>{{$item->recipient ? $item->recipient : '-'}}</td>
                    <td>{{isset($item->nominal) ? format_idr($item->nominal) : '-'}}</td>
                    <td>{{isset($item->bank_account->no_rekening) ? $item->bank_account->no_rekening .'('.$item->bank_account->bank.')' : '-'}}</td>
                    <td>{{isset($item->outstanding_balance) ? format_idr($item->outstanding_balance) : '-'}}</td>
                    <td>{{isset($item->payment_amount) ? format_idr($item->payment_amount) : '-'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br />
    {{$data->links()}}
</div>
@push('after-scripts')
<script>
    $('.payment_date_expense').daterangepicker({
        opens: 'left'
    }, function(start, end, label) {
        @this.set("payment_date_from", start.format('YYYY-MM-DD'));
        @this.set("payment_date_to", end);
    });
</script>
@endpush