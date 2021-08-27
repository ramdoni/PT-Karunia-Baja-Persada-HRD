@section('title', gl_number($data))
@section('parentPageTitle', 'General Ledger')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-8">                        
                        <a href="javascript:void(0)" class="btn btn-success" wire:click="download"><i class="fa fa-download"></i> Download Report</a>
                        <a href="{{route('general-ledger.revisi',$data->id)}}" class="btn btn-info"><i class="fa fa-edit"></i> Revisi</a>
                        <a href="{{route('general-ledger.revisi-history',$data->id)}}" class="btn btn-warning"><i class="fa fa-history"></i> History Revisi</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <hr />
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-bordered">
                        <thead style="background: #eee;">
                            <tr>
                                <th></th>
                                <th>No Voucher</th>
                                <th>Date</th>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        @foreach(\App\Models\Coa::where('coa_group_id',$coa_group->id)->get() as $coa)
                            @if(\App\Models\Journal::where(['general_ledger_id'=>$data->id,'coa_id'=>$coa->id])->count()==0) @continue @endif
                            <tr>
                                <td>{{$coa->code}}</td>
                                <th colspan="7">{{$coa->name}}</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <th colspan="6">Saldo Awal</th>
                                <td class="text-right">{{format_idr($coa->opening_balance)}}</td>
                            </tr>
                            @php($total_debit=0)
                            @php($total_kredit=0)
                            @php($total_saldo=$coa->opening_balance)
                            @foreach(\App\Models\Journal::where(['general_ledger_id'=>$data->id,'coa_id'=>$coa->id])->get() as $journal)
                                <tr>
                                    <td class="text-center"></td>
                                    <td>{{$journal->no_voucher}}</td>
                                    <td>{{date('d-M-Y',strtotime($journal->date_journal))}}</td>
                                    <td>{{isset($journal->coa->name)?$journal->coa->name : ''}}</td>
                                    <td>{{$journal->description}}</td>
                                    <td class="text-right">{{format_idr($journal->debit)}}</td>
                                    <td class="text-right">{{format_idr($journal->kredit)}}</td>
                                    <td class="text-right">{{format_idr($journal->saldo)}}</td>
                                </tr>
                                @php($total_debit +=$journal->debit)
                                @php($total_kredit +=$journal->kredit)
                                @php($total_saldo -=$journal->saldo)
                            @endforeach
                            @if(\App\Models\Journal::where(['coa_id'=>$coa->id])->count()==0)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                            <thead style="background: #eee;">
                                <tr>
                                    <th colspan="5" class="text-center">Total {{$coa->name}}</th>
                                    <th class="text-right">{{format_idr($total_debit)}}</th>
                                    <th class="text-right">{{format_idr($total_kredit)}}</th>
                                    <th class="text-right">{{format_idr($total_saldo)}}</th>
                                </tr>
                            </thead>
                            <tr>
                                <td colspan="9" class="py-2" style="border-left:0;border-right:0;"></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

