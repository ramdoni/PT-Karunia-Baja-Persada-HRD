<div>
    <div class="mb-2 row">
        <div class="col-md-2">
            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
        </div>
        <div class="px-0 col-md-1">
            <select class="form-control" wire:model="status">
                <option value=""> --- Status --- </option>
                <option value="1"> Save as Draft </option>
                <option value="2"> Journal</option>
            </select>
        </div>
        <div class="col-md-1">
            <a href="{{route('operation.payable.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Account Payable</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped m-b-0 c_list table-hover">
            <thead>
                <tr>
                    <th class="align-middle">No</th>
                    <th>Status</th>
                    <th class="align-middle">No Voucher</th>                                    
                    <th class="align-middle">Recipient</th>                                    
                    <th class="align-middle">Reference Type</th>                                    
                    <th class="align-middle">Reference No.</th>
                    <th class="align-middle">Referense Date</th>
                    <th class="align-middle">Description</th>
                    <th>Amount</th>
                    <th>Bank Code</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $k => $item)
                <tr style="cursor:pointer;" onclick="document.location='{{route('operation.payable.edit',['id'=>$item->id])}}'">
                    <td style="width: 50px;">{{$k+1}}</td>
                    <td>{!!status_expense($item->status)!!}</td>
                    <td>{{$item->no_voucher ? $item->no_voucher : '-'}}</td>
                    <td>{{$item->recipient ? $item->recipient : '-'}}</td>
                    <td>{{$item->reference_type ? $item->reference_type : '-'}}</td>
                    <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                    <td>{{$item->reference_date ? $item->reference_date : '-'}}</td>
                    <td>{{$item->description ? $item->description : '-'}}</td>
                    <td>{{isset($item->nominal) ? format_idr($item->nominal) : '-'}}</td>
                    <td>{{isset($item->bank_account->code)?$item->bank_account->code : '-'}}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br />
        {{$data->links()}}
    </div>
</div>