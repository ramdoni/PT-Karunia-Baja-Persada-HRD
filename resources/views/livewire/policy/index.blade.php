@section('title', 'Polis')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-10">
                    <label class="fancy-checkbox">
                        <input type="checkbox" name="checkbox" wire:model="is_reas">
                        <span>Reas</span>
                    </label>
                    <a href="{{route('policy.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Polis</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor</th>                                    
                                <th>Pemegang Polis</th>                                    
                                <th>Alamat</th>                                    
                                <th>Cabang</th>
                                <th>Produk</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>
                                    {{$item->no_polis}}
                                    @if($item->is_reas==1)
                                    <span class="badge badge-warning">R</span>
                                    @endif
                                </td>
                                <td>{{$item->pemegang_polis}}</td>
                                <td>{{$item->alamat}}</td>
                                <td>{{$item->cabang}}</td>
                                <td>{{$item->produk}}</td>
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