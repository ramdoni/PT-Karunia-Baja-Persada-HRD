@section('title', 'Account Payable')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="pr-0 col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="coa_id">
                        <option value=""> --- COA --- </option>
                        @foreach(\App\Models\Coa::orderBy('name','ASC')->get() as $i)
                        <option value="{{$i->id}}">{{$i->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="px-0 col-md-1">
                    <select class="form-control" wire:model="status">
                        <option value=""> --- Status --- </option>
                        <option value="1"> Waiting </option>
                        <option value="2"> Kurang Bayar </option>
                        <option value="3"> Complete </option>
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{route('account-payable.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Account Payable</a>
                </div>
            </div>
            <div class="pt-0 body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list">
                        <thead>
                            <tr>
                                <th class="align-middle">No</th>
                                <th class="align-middle">No Voucher</th>                                    
                                <th class="align-middle">Recipient</th>                                    
                                <th class="align-middle">Reference Type</th>                                    
                                <th class="align-middle">Reference No.</th>
                                <th class="align-middle">Referense Date</th>
                                <th class="align-middle">Description</th>
                                <th><div style="width:100px;">Tax Inclusive Amount</div></th>
                                <th class="align-middle">Tax Code</th>
                                <th class="align-middle">Exclusive Amount</th>
                                <th>Tax Amount</th>
                                <th>Outstanding Balance</th>
                                <th>Status</th>
                                <th>Account Number</th>
                                <th>Payment Amount</th>
                                <th>Payment Date</th>
                                <th>Bank Code</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td><a href="{{route('account-payable.edit',['id'=>$item->id])}}">{{$item->no_voucher ? $item->no_voucher : '-'}}</a></td>
                                <td>
                                    <a href="{{route('account-payable.edit',['id'=>$item->id])}}">
                                    {{$item->recipient ? $item->recipient : '-'}}
                                    </a>
                                </td>
                                <td>{{$item->reference_type ? $item->reference_type : '-'}}</td>
                                <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                                <td>{{$item->reference_date ? $item->reference_date : '-'}}</td>
                                <td>{{$item->description ? $item->description : '-'}}</td>
                                <td>{{isset($item->tax->name) ? $item->tax->name : '-'}}</td>
                                <td>{{isset($item->tax->code) ? $item->tax->code : '-'}}</td>
                                <td>{{isset($item->amount) ? $item->amount : '-'}}</td>
                                <td></td>
                                <td>{{format_idr($item->outstanding_balance)}}</td>
                                <td>{!!status_expense($item->status)!!}</td>
                                <td></td>
                                <td>{{format_idr($item->payment_amount)}}</td>
                                <td>{{date('d-M-Y',strtotime($item->payment_date))}}</td>
                                <td>{{isset($item->bank->code)?$item->bank->code : '-'}}</td>
                                <td>
                                    @if($item->status!=3)
                                    <a href="{{route('account-payable.edit',['id'=>$item->id])}}" class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i> Process</a>
                                    @endif
                                </td>
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