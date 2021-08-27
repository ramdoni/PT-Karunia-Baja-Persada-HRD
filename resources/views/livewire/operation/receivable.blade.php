<div class="pt-0">
    <div class="mb-2 row">
        <div class="col-md-2">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
        </div>
        <div class="px-0 col-md-1">
            <select class="form-control" wire:model="status">
                <option value=""> --- Status --- </option>
                <option value="1"> Save as Draft </option>
                <option value="2"> Journal </option>
            </select>
        </div>
        <div class="col-md-1">
            <a href="{{route('operation.receivable.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Account Receivable</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover m-b-0 c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>Voucher No</th>                                    
                    <th>Client</th>                                    
                    <th>Reference Type</th>                                    
                    <th>Reference No</th>                                    
                    <th>Reference Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Bank Code</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $k => $item)
                    <tr onclick="document.location='{{route('operation.receivable.edit',['id'=>$item->id])}}'" style="cursor:pointer;">
                        <td style="width: 50px;">{{$k+1}}</td>
                        <td>{!!status_income($item->status)!!}</td>
                        <td>{{$item->no_voucher ? $item->no_voucher : '-'}}</td>
                        <td>{{$item->client ? $item->client : '-'}}</td>
                        <td>{{$item->reference_type ? $item->reference_type : '-'}}</td>
                        <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                        <td>{{$item->reference_date ? $item->reference_date : '-'}}</td>
                        <td>{{$item->description ? $item->description : '-'}}</td>
                        <td>{{format_idr($item->nominal)}}</td>
                        <td>{{isset($item->bank_account->code)?$item->bank_account->code : '-'}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br />
    {{$data->links()}}
</div>