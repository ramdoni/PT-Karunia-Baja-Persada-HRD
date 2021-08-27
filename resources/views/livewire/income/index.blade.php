@section('title', 'Income')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
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
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>                                    
                                <th>Status</th>                                    
                                <th>No Voucher</th>                                    
                                <th>Client</th>                                    
                                <th>Reference Type</th>                                    
                                <th>Reference No</th>                                    
                                <th>Reference Date</th>
                                <th>Description</th>
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
                            <tr onclick="document.location='{{route('income.edit',['id'=>$item->id])}}'" style="cursor:pointer;">
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>{!!status_income($item->status)!!}</td>
                                <td>{{$item->no_voucher}}</td>
                                <td>{{$item->client ? $item->client : '-'}}</td>
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
                </div>
                <br />
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>