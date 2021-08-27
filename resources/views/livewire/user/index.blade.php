@section('title', 'All User')
@section('parentPageTitle', 'Management User')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="user_access_id">
                        <option value="">--- User Access ---</option>
                        @foreach(\App\Models\UserAccess::all() as $i)
                        <option value="{{$i->id}}">{{$i->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{route('users.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> User</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>                                    
                                <th>Phone</th>                                    
                                <th>Email</th>                                    
                                <th>Address</th>
                                <th>Access</th>
                                <th>Updated</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$k+1}}</td>
                                <td>
                                    @if(\Auth::user()->id!=$item->id)
                                    <a href="#" class="pr-2 text-success" onclick="autologin('{{ route('users.autologin',['id'=>$item->id]) }}','{{$item->name}}')" title="Autologin"><i class="fa fa-sign-in"></i></a> 
                                    @endif
                                    <a href="{{route('users.edit',['id'=>$item->id])}}">{{$item->name}}</a>
                                </td>
                                <td>{{$item->telepon}}</td> 
                                <td>{{$item->email}}</td>                                   
                                <td>{{$item->address}}</td>
                                <td>{{isset($item->access->name)?$item->access->name:''}}</td>
                                <td>{{$item->updated_at}}</td>
                                <td>  
                                    @if(\Auth::user()->id!=$item->id)
                                    <a href="#" class="pr-2 text-success" onclick="autologin('{{ route('users.autologin',['id'=>$item->id]) }}','{{$item->name}}')" title="Autologin"><i class="fa fa-sign-in"></i></a>
                                    @endif
                                    <a href="#" class="text-danger" wire:click="delete({{$item->id}})" title="Delete"><i class="fa fa-trash-o"></i></a>
                                    {{-- <a href="#" class="text-danger" data-toggle="modal" data-target="#confirm_delete" wire:click="setActiveId({{$item->id}})" title="Delete"><i class="fa fa-trash-o"></i></a> --}}
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

<div class="modal fade" id="modal_autologin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-sign-in"></i> Autologin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger close-modal">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirm_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-warning"></i> Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <p>Are you want delete this data ?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">No</button>
                <button type="button" wire:click="delete()" class="btn btn-danger close-modal">Yes</button>
            </div>
        </div>
    </div>
</div>
@section('page-script')
function autologin(action,name){
    $("#modal_autologin form").attr("action",action);
    $("#modal_autologin .modal-body").html('<p>Autologin as '+name+' ?</p>');
    $("#modal_autologin").modal("show");
}
@endsection