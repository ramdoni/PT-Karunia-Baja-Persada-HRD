@section('title', __('Vendor'))
@section('parentPageTitle', 'Home')

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-1">
                    <a href="{{route('vendor.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> {{__('Vendor')}}</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>PIC</th>                                    
                                <th>Vendor</th>                                    
                                <th>Phone</th>                                    
                                <th>Fax</th>                                    
                                <th>Email</th>                                    
                                <th>Address</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>{{$item->pic}}</td>
                                <td><a href="{{route('vendor.edit',['id'=>$item->id])}}">{{$item->name}}</a></td>
                                <td>{{$item->phone}}</td> 
                                <td>{{$item->fax}}</td>                                   
                                <td>{{$item->email}}</td>                                   
                                <td>{{$item->address}}</td>
                                <td></td>
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