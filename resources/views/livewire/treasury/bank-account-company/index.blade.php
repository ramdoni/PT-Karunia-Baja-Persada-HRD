@section('title', 'Bank Account')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-1">
                    <a href="{{route('bank-account-company.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Bank Account</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code</th>                                    
                                <th>Bank</th>                                    
                                <th>No Rekening</th>                                    
                                <th>Owner</th>                                    
                                <th>Cabang</th>
                                <th>Open Balance</th>
                                <th>Chart of Account</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td><a href="{{route('bank-account-company.edit',['id'=>$item->id])}}">{{$item->code}}</a></td>
                                <td><a href="{{route('bank-account-company.edit',['id'=>$item->id])}}">{{$item->bank}}</a></td>
                                <td>{{$item->no_rekening}}</td>
                                <td>{{$item->owner}}</td>
                                <td>{{$item->cabang}}</td>
                                <td>{{format_idr($item->open_balance)}}</td>
                                <td>{{isset($item->coa->name)?$item->coa->code.' - '.$item->coa->name : ''}}</td>
                                <td><a href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger"><i class="fa fa-trash"></i></a></td>
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