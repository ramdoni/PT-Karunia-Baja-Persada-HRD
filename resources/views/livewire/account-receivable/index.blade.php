@section('title', 'Account Receivable')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="px-0 col-md-2">
                    <select class="form-control" wire:model="coa_id">
                        <option value=""> --- COA --- </option>
                        @foreach(\App\Models\Coa::orderBy('name','ASC')->get() as $i)
                        <option value="{{$i->id}}">{{$i->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <select class="form-control" wire:model="status">
                        <option value=""> - Status - </option>
                        <option value="1"> Waiting </option>
                        <option value="2"> Kurang Bayar </option>
                        <option value="3"> Complete </option>
                    </select>
                </div>
                <div class="px-0 col-md-1">
                    <a href="{{route('account-receivable.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Account Receivable</a>
                </div>
            </div>
            <div class="pt-0 body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Voucher No</th>                                    
                                <th>Debit Note</th>                                    
                                <th>DN Date</th>                                    
                                <th>Police No</th>                                    
                                <th>Customer/Police Holder</th>
                                <th>Description</th>
                                <th>Tax Inclusive Amount</th>
                                <th>Tax Code</th>
                                <th>Exclusive Amount</th>
                                <th>Tax Amount</th>
                                <th>Outstanding Balance</th>
                                <th>Status</th>
                                <th>Account Number</th>
                                <th>Received Amount</th>
                                <th>Payment Date</th>
                                <th>Bank Code</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td>{{$item->no_voucher ? $item->no_voucher : '-'}}</td>
                                    <td>
                                        @if($item->status==3)
                                            <a href="{{route('account-receivable.view',['id'=>$item->id])}}">{{$item->debit_note ? $item->debit_note : '-'}}</a>
                                        @else
                                            <a href="{{route('account-receivable.edit',['id'=>$item->id])}}">{{$item->debit_note ? $item->debit_note : '-'}}</a>
                                        @endif
                                    </td>
                                    <td>{{$item->debit_note_date ? $item->debit_note_date : '-'}}</td>
                                    <td>{{isset($item->policys->no_polis) ? $item->policys->no_polis : '-'}}</td>
                                    <td>{{isset($item->policys->pemegang_polis) ? $item->policys->pemegang_polis : '-'}}</td>
                                    <td>{{$item->description ? $item->description : '-'}}</td>
                                    <td>{{format_idr($item->nominal)}}</td>
                                    <td>{{isset($item->tax->name) ? $item->tax->name : '-'}}</td>
                                    <td>{{isset($item->tax->code) ? $item->tax->code : '-'}}</td>
                                    <td>{{isset($item->amount) ? $item->amount : '-'}}</td>
                                    <td>{{format_idr($item->outstanding_balance)}}</td>
                                    <td><a href="{{route('account-receivable.edit',['id'=>$item->id])}}">{!!status_income($item->status)!!}</a></td>
                                    <td></td>
                                    <td>{{format_idr($item->payment_amount)}}</td>
                                    <td>{{date('d-M-Y',strtotime($item->payment_date))}}</td>
                                    <td>{{isset($item->bank_account->code)?$item->bank_account->code : '-'}}</td>
                                    <td>
                                        @if($item->status!=3)
                                        <a href="{{route('account-receivable.edit',['id'=>$item->id])}}" class="btn btn-info btn-sm"><i class="fa fa-arrow-right"></i> Process</a>
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