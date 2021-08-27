@section('title', 'Code Cashflow')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-5">
        <div class="card">
            <div class="header row">
                <div class="col-md-4">
                    <a href="{{route('code-cashflow.insert')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Code Cashflow</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list">
                        <tbody>
                            @foreach(get_group_cashflow() as $k =>$i)
                                <tr>
                                    <th colspan="2">{{$i}}</th>
                                </tr>
                                @foreach(\App\Models\CodeCashflow::where('group',$k)->get() as $k => $item)
                                <tr>
                                    <td><a href="{{route('code-cashflow.edit',['id'=>$item->id])}}">{{$item->name}}</a></td>
                                    <td><a href="{{route('code-cashflow.edit',['id'=>$item->id])}}">{{$item->code}}</a></td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>