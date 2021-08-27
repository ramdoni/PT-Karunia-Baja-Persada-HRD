@section('title', 'Expense')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="px-0 col-md-1">
                    <select class="form-control" wire:model="status">
                        <option value=""> --- Status --- </option>
                        <option value="1"> Waiting </option>
                        <option value="2"> Completed</option>
                    </select>
                </div>
            </div>
            <div class="pt-0 body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle">No</th>
                                <th class="align-middle">Status</th>                                    
                                <th class="align-middle">Recipient</th>                                    
                                <th class="align-middle">Reference Type</th>                                    
                                <th class="align-middle">Reference No.</th>
                                <th class="align-middle">Referense Date</th>
                                <th class="align-middle">Description</th>
                                <th>Tax Inclusive Amount</th>
                                <th>Tax Code</th>
                                <th>Exclusive Amount</th>
                                <th>Tax Amount</th>
                                <th>Outstanding Balance</th>
                                <th>Payment Amount</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr style="cursor:pointer;" onclick="document.location='{{route('expense.edit',['id'=>$item->id])}}'">
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>{!!status_expense($item->status)!!}</td>
                                <td>{{$item->recipient ? $item->recipient : '-'}}</td>
                                <td>{{$item->reference_type ? $item->reference_type : '-'}}</td>
                                <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                                <td>{{$item->reference_date ? $item->reference_date : '-'}}</td>
                                <td>{{$item->description ? $item->description : '-'}}</td>
                                <td>{{isset($item->nominal) ? format_idr($item->nominal+$item->tax_amount) : '-'}}</td>
                                <td>{!!isset($item->tax->code) ? '<span title="'.$item->tax->description .' ('. $item->tax->percen.'%)">'. $item->tax->code .'</span>' : '-'!!}</td>
                                <td>{{isset($item->nominal) ? format_idr($item->nominal) : '-'}}</td>
                                <td>{{$item->tax_amount?format_idr($item->tax_amount):'-'}}</td>
                                <td>{{isset($item->outstanding_balance) ? format_idr($item->outstanding_balance) : '-'}}</td>
                                <td>{{isset($item->payment_amount) ? format_idr($item->payment_amount) : '-'}}</td>
                                <td>{{date('d M Y',strtotime($item->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br />
                    {{$data->links()}}
                </div>
            </div>
        </div>
    </div>
</div>