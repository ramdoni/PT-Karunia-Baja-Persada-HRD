@section('title', 'Journal Penyesuaian')
@section('parentPageTitle', 'Home')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-3" wire:ignore>
                        <select class="form-control coa_id" id="coa_id" wire:model="coa_id">
                            <option value=""> -- COA -- </option>
                            @foreach(\App\Models\CoaGroup::orderBy('name')->get() as $group)
                                <optgroup label="{{$group->name}}">
                                @foreach(\App\Models\Coa::where('coa_group_id',$group->id)->get() as $k => $coa)
                                <option value="{{$coa->id}}">{{$coa->code}} {{$coa->name}}</option>
                                @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-1">
                        <select class="form-control" wire:model="year">
                            <option value=""> - Year - </option>
                            @foreach(\App\Models\Journal::select( DB::raw( 'YEAR(date_journal) AS year' ))->groupBy('year')->get() as $i)
                            <option>{{$i->year}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select class="form-control" wire:model="month">
                            <option value=""> -- Month -- </option>
                            @foreach(month() as $k=>$i)
                            <option value="{{$k}}">{{$i}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        {{-- <a href="javascript:;" class="btn btn-success" data-toggle="modal" data-target="#modal_add_journal"><i class="fa fa-plus"></i> Journal</a>
                        <a href="javascript:void(0)" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download</a> --}}
                        <div wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="px-0 body">
                    <div class="table-responsive">
                        <table class="table m-b-0 c_list table-bordered table-style1">
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
                                </tr>
                            </thead>
                            
                            @foreach($data as $key => $journal)
                            {{-- @if($key!=0)
                            <tr>                    
                                <th colspan="8"></th>                                    
                            </tr>
                            @endif --}}
                            <tbody>
                                @foreach(\App\Models\Journal::where('no_voucher',$journal['no_voucher'])->get() as $item)
                                <tr style="background: #eeeeeeee">
                                    <td>{{isset($item->coa->code)?$item->coa->code:''}}</td>
                                    <td><a href="{{route('accounting-journal.detail',['id'=>$item->id])}}">{{$item->no_voucher}}</a></td>
                                    <td>{{date('d-M-Y',strtotime($item->date_journal))}}</td>
                                    <td>{{isset($item->coa->name)?$item->coa->name:''}}</td>
                                    <td>{{$item->description}}</td>
                                    <td class="text-right">{{format_idr($item->debit)}}</td>
                                    <td class="text-right">{{format_idr($item->kredit)}}</td>
                                    <td class="text-right">{{format_idr($item->saldo)}}</td>
                                </tr>
                                @endforeach
                                @php($br_=0)
                                @foreach(\App\Models\JournalPenyesuaian::where('journal_no_voucher',$journal->no_voucher)->get() as $p)
                                    @if($p->no_voucher!=$br_)
                                        <tr><td colspan="9" style="background:#bbbaba;padding:1px;"></td></tr>
                                    @endif
                                    <tr>
                                        <td>{{isset($p->coa->code)?$p->coa->code:''}}</td>
                                        <td><a href="{{route('accounting-journal.detail',['id'=>$p->id])}}">{{$p->no_voucher}}</a></td>
                                        <td>{{date('d-M-Y',strtotime($p->date_journal))}}</td>
                                        <td>{{isset($p->coa->name)?$p->coa->name:''}}</td>
                                        <td>{{$p->description}}</td>
                                        <td class="text-right">{{format_idr($p->debit)}}</td>
                                        <td class="text-right">{{format_idr($p->kredit)}}</td>
                                        <td class="text-right">{{format_idr($p->saldo)}}</td>
                                    </tr>
                                    @php($br_=$p->no_voucher)
                                @endforeach
                            </tbody>
                            <tr>
                                <td colspan="9" class="py-1" style="border-left:0;border-right:0;"></td>
                            </tr>
                        @endforeach
                        </table>
                        {{-- <table class="table m-b-0 c_list table-bordered table-style1">
                            <thead style="background:#eee;">
                                <tr>                    
                                    <th>COA</th>                                    
                                    <th>No Voucher</th>                                    
                                    <th>Journal Date</th>                                    
                                    <th>Account</th>                                    
                                    <th>Description</th>                                    
                                    <th>Debit</th>                                    
                                    <th>Kredit</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($br=0)
                                @foreach($data as $k => $item)
                                @if($item->no_voucher!=$br)
                                <tr>
                                    <td colspan="8" style="padding-left:30px !important;">
                                        <label>Journal Penyesuaian</label>
                                        <table class="table">
                                            <tr>
                                                <th>COA</th>                                    
                                                <th>No Voucher</th>                                    
                                                <th>Journal Date</th>                                    
                                                <th>Account</th>                                    
                                                <th>Description</th>                                    
                                                <th>Debit</th>                                    
                                                <th>Kredit</th>
                                                <th>Saldo</th>
                                            </tr>
                                            @php($br_=0)
                                            @foreach($item->penyesuaian as $p)
                                                @if($p->no_voucher!=$br_)
                                                    <tr><td colspan="9" style="background:#bbbaba;padding:1px;"></td></tr>
                                                @endif
                                                <tr>
                                                    <td>{{isset($p->coa->code)?$p->coa->code:''}}</td>
                                                    <td><a href="{{route('accounting-journal.detail',['id'=>$p->id])}}">{{$p->no_voucher}}</a></td>
                                                    <td>{{date('d-M-Y',strtotime($p->date_journal))}}</td>
                                                    <td>{{isset($p->coa->name)?$p->coa->name:''}}</td>
                                                    <td>{{$p->description}}</td>
                                                    <td class="text-right">{{format_idr($p->debit)}}</td>
                                                    <td class="text-right">{{format_idr($p->kredit)}}</td>
                                                    <td class="text-right">{{format_idr($p->saldo)}}</td>
                                                </tr>
                                                @php($br_=$p->no_voucher)
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                <tr><td colspan="9" style="background:#bbbaba;padding:1px;"></td></tr>
                                <thead style="background:#eee;">
                                    <tr>                    
                                        <th>COA</th>                                    
                                        <th>No Voucher</th>                                    
                                        <th>Journal Date</th>                                    
                                        <th>Account</th>                                    
                                        <th>Description</th>                                    
                                        <th>Debit</th>                                    
                                        <th>Kredit</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                @endif
                                <tr>
                                    <td>{{isset($item->coa->code)?$item->coa->code:''}}</td>
                                    <td><a href="{{route('accounting-journal.detail',['id'=>$item->id])}}">{{$item->no_voucher}}</a></td>
                                    <td>{{date('d-M-Y',strtotime($item->date_journal))}}</td>
                                    <td>{{isset($item->coa->name)?$item->coa->name:''}}</td>
                                    <td>{{$item->description}}</td>
                                    <td class="text-right">{{format_idr($item->debit)}}</td>
                                    <td class="text-right">{{format_idr($item->kredit)}}</td>
                                    <td class="text-right">{{format_idr($item->saldo)}}</td>
                                </tr>
                                @php($br=$item->no_voucher)
                                
                                @if(($k+1)==$data->count())
                                <tr>
                                    <td colspan="8"  style="padding-left:30px !important;">
                                        <label>Journal Penyesuaian</label>
                                        <table class="table">
                                            <tr>
                                                <th>COA</th>                                    
                                                <th>No Voucher</th>                                    
                                                <th>Journal Date</th>                                    
                                                <th>Account</th>                                    
                                                <th>Description</th>                                    
                                                <th>Debit</th>                                    
                                                <th>Kredit</th>
                                                <th>Saldo</th>
                                            </tr>
                                            @php($br_=0)
                                            @foreach($item->penyesuaian as $p)
                                                @if($p->no_voucher!=$br_)
                                                    <tr><td colspan="9" style="background:#bbbaba;padding:1px;"></td></tr>
                                                @endif
                                                <tr>
                                                    <td>{{isset($p->coa->code)?$p->coa->code:''}}</td>
                                                    <td><a href="{{route('accounting-journal.detail',['id'=>$p->id])}}">{{$p->no_voucher}}</a></td>
                                                    <td>{{date('d-M-Y',strtotime($p->date_journal))}}</td>
                                                    <td>{{isset($p->coa->name)?$p->coa->name:''}}</td>
                                                    <td>{{$p->description}}</td>
                                                    <td class="text-right">{{format_idr($p->debit)}}</td>
                                                    <td class="text-right">{{format_idr($p->kredit)}}</td>
                                                    <td class="text-right">{{format_idr($p->saldo)}}</td>
                                                </tr>
                                                @php($br_=$p->no_voucher)
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                                @endif

                                @endforeach
                            </tbody> --}}
                        </table>
                    </div>
                    <br />
                    {{$data->links()}}
                    <div class="modal fade" id="modal_add_journal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <livewire:accounting-journal.insert>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;padding-left:10px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
<script>
    select__2 = $('.coa_id').select2();
    $('.coa_id').on('change', function (e) {
        let elementName = $(this).attr('id');
        var data = $(this).select2("val");
        @this.set(elementName, data);
    });
    var selected__ = $('.coa_id').find(':selected').val();
    if(selected__ !="") select__2.val(selected__);
</script>
@endpush
@section('page-script')
    Livewire.on('message', msg =>{
        alert(msg);
    });
    Livewire.on('modalEdit', () =>{
        $("#modal_set_code_cashflow").modal("show");
    });
    Livewire.on('modalEditHide', () =>{
        $("#modal_set_code_cashflow").modal("hide");
    });
    Livewire.on('modalSetCodeCashflowCheckbox', () =>{
        $("#modal_set_code_cashflow_checkbox").modal("show");
    });
    Livewire.on('modalSetCodeCashflowCheckboxHide', () =>{
        $("#modal_set_code_cashflow_checkbox").modal("hide");
    });
@endsection
</div>