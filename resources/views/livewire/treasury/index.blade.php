@section('title', 'Transactions')
@section('parentPageTitle', 'Dashboard')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
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
                        <a href="javascript:void(0)" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-file-excel-o"></i> Download</a>
                        @if($set_multiple_bank)
                            <a href="javascript:void(0)" class="btn btn-danger" wire:click="$set('set_multiple_bank',false)"><i class="fa fa-times"></i> Cancel</a>
                            <a href="javascript:void(0)" class="btn btn-success" wire:click="submitBank"><i class="fa fa-check"></i> Submit</a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-warning" wire:click="$set('set_multiple_bank',true)"><i class="fa fa-check"></i> Set Bank</a>
                        @endif
                    </div>
                </div>
                <div class="px-0 body">
                    <div class="table-responsive">
                        <table class="table table-striped m-b-0 c_list table-bordered table-style1 table-hover">
                            <thead>
                                <tr>                    
                                    <th>No</th>
                                    <th>Invoice / Debit Note</th>                                    
                                    <th>COA</th>                                    
                                    <th>No Voucher</th>                                    
                                    <th>Date</th>                                    
                                    <th>Account</th>                                    
                                    <th>Description</th>                                    
                                    <th>Debit</th>                                    
                                    <th>Kredit</th>
                                    <th>Saldo</th>
                                    <th style="text-align:center;">Cash Flow Code</th>
                                    <th>
                                        @if($set_multiple_bank)
                                            <label class="text-succes" wire:click="checkAll"><input type="checkbox" wire:model="check_all" value="1" /> Check All</label>
                                        @else
                                            Bank Code
                                        @endif
                                    </th>
                                    <th>Transaction Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($br=0)
                                @php($key_code_bank=0)
                                @php($num=$data->firstItem())
                                @foreach($data as $k => $item)
                                @if($item->no_voucher!=$br)
                                <tr><td colspan="13"></td></tr>
                                @endif
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>{{$item->transaction_number}}</td>
                                    <td>{{isset($item->coa->code)?$item->coa->code:''}}</td>
                                    <td>
                                        @if($item->transaction_table =='income')
                                        <a href="{{route('account-receivable.view',['id'=>$item->transaction_id])}}">{{$item->no_voucher}}</a>
                                        @elseif($item->transaction_table =='expenses')
                                        <a href="{{route('account-payable.view',['id'=>$item->transaction_id])}}">{{$item->no_voucher}}</a>
                                        @else
                                        {{$item->no_voucher}}
                                        @endif
                                    </td>
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
                                    <td class="text-center">
                                        @if(!isset($item->bank->code))
                                            @if($set_multiple_bank)
                                                <input type="checkbox" wire:model="value_multiple_bank.{{$key_code_bank}}" value="{{$item->id}}" />
                                                @php($key_code_bank++)
                                            @else
                                                <a href="javascript:void(0)" wire:click="setBank({{$item->id}})"><i class="fa fa-edit"></i> Set Bank</a>
                                            @endif
                                        @else
                                            <span title="{{$item->bank->bank}} - {{$item->bank->no_rekening}} an {{$item->bank->owner}}">{{$item->bank->code}}</span>
                                        @endif
                                    </td>
                                    <td>{{$item->transaction_date!=""? date('d M Y', strtotime($item->transaction_date)) :''}}</td>
                                </tr>
                                @php($br=$item->no_voucher)
                                @php($num++)
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-6">
                            {{$data->links()}}
                        </div>
                        <div class="text-right col-md-6">Total : {{format_idr($data->total())}}</div>
                    </div>
                    <div wire:ignore.self class="modal fade" id="modal_set_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <livewire:treasury.set-bank>
                            </div>
                        </div>
                    </div>
                    <div wire:ignore.self class="modal fade" id="modal_set_bank_checkbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <livewire:treasury.set-bank-checkbox>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @section('page-script')
                Livewire.on('message', msg =>{
                    alert(msg);
                });
                Livewire.on('modalSetBank', () =>{
                    $("#modal_set_bank").modal("show");
                });
                Livewire.on('hideModalSetBank', () =>{
                    $("#modal_set_bank").modal("hide");
                });
                Livewire.on('modalSetBankMultiple', () =>{
                    $("#modal_set_bank_checkbox").modal("show");
                });
                Livewire.on('hideModalSetBankMultiple', () =>{
                    $("#modal_set_bank_checkbox").modal("hide");
                });
                $( document ).ready(function() {
                    $(".btn-toggle-fullwidth").trigger('click');
                });
            @endsection
        </div>
    </div>
</div>