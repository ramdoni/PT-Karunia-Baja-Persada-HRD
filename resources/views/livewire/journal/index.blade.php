<div>
    <div class="row">
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
        <div class="px-0 col-md-2">
            <select class="form-control" wire:model="code_cashflow_id">
                <option value=""> --- Code Cash Flow --- </option>
                @foreach(get_group_cashflow() as $k=>$i)
                <optgroup label="{{$i}}">
                    @foreach(\App\Models\CodeCashflow::where('group',$k)->get() as $k => $item)
                        <option value="{{$item->id}}">{{$item->code}} - {{$item->name}}</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <a href="javascript:void(0)" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a>
        </div>
    </div>
    <div class="px-0 body">
        <div class="table-responsive">
            <table class="table table-striped m-b-0 c_list table-bordered table-style1 table-hover">
                <thead>
                    <tr>                    
                        <th>COA</th>                                    
                        <th>No Voucher</th>                                    
                        <th>Journal Date</th>                                    
                        <th>Account</th>                                    
                        <th>Description</th>                                    
                        <th>Debit</th>                                    
                        <th>Kredit</th>
                        <th>Saldo</th>
                        <th style="text-align:center;">Code Cashflow</th>
                    </tr>
                </thead>
                <tbody>
                    @php($br=0)
                    @php($key_code_cashflow=0)
                    @foreach($data as $k => $item)
                    @if($item->no_voucher!=$br)
                    <tr><td colspan="9"></td></tr>
                    @endif
                    <tr>
                        <td>{{isset($item->coa->code)?$item->coa->code:''}}</td>
                        <td>{{$item->no_voucher}}</td>
                        <td>{{date('d-M-Y',strtotime($item->date_journal))}}</td>
                        <td>{{isset($item->coa->name)?$item->coa->name:''}}</td>
                        <td>{{$item->description}}</td>
                        <td class="text-right">{{format_idr($item->debit)}}</td>
                        <td class="text-right">{{format_idr($item->kredit)}}</td>
                        <td class="text-right">{{format_idr($item->saldo)}}</td>
                        <td style="text-align:center;">
                            @if(isset($item->code_cashflow->code))
                                <span>{{$item->code_cashflow->code}}</span>
                            @endif
                        </td>
                    </tr>
                    @php($br=$item->no_voucher)
                    @endforeach
                </tbody>
            </table>
        </div>
        <br />
        {{$data->links()}}
    </div>
</div>