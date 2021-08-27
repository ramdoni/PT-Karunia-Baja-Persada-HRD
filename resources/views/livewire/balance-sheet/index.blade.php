@section('title', 'Balance Sheet')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <select class="form-control" wire:model="coa_id">
                        <option value=""> --- COA --- </option>
                        @foreach(\App\Models\Coa::orderBy('name','ASC')->get() as $k=>$i)
                        <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pl-0 col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="px-0 col-md-1">
                    <select class="form-control" wire:model="year">
                        <option value=""> -- Year -- </option>
                        @foreach(\App\Models\Journal::select( DB::raw( 'YEAR(date_journal) AS year' ))->groupBy('year')->get() as $i)
                        <option>{{$i->year}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model="month">
                        <option value=""> --- Month --- </option>
                        @foreach(month() as $k=>$i)
                        <option value="{{$k}}">{{$i}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="px-0 col-md-4">
                    <a href="javascript:void(0)" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download Excel</a>
                </div>
            </div>
            <div class="pt-0 body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list">
                        <thead>
                            <tr>       
                                <th>Key</th>                                    
                                <th>Description</th>          
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k => $item)
                            <tr>
                                <td>{{isset($item->coa->code)?$item->coa->code:''}}</td>
                                <td>{{isset($item->coa->name)?$item->coa->name:''}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>