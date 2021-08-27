@section('title', 'History Revisi : '.$data->general_ledger_number)
@section('parentPageTitle', 'General Ledger')
<div class="clearfix row">
    <div class="col-lg-12">
        @foreach($histories as $history)
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-2">
                        <h6 class="text-success">{{$history->gl->general_ledger_number}}{{$history->is_revisi!=0 ? "-R".$history->is_revisi : ''}}</h6>
                        <span>{{date('d M Y',strtotime($history->created_at))}}</span>
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:;" wire:click="downloadReport({{$history->is_revisi}})" class="badge badge-info"><i class="fa fa-download"></i> Download Report</a>
                    </div>
                </div>
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
                        @foreach(\App\Models\Coa::where('coa_group_id',$data->coa_group_id)->get() as $coa)
                            @if(\App\Models\GeneralLedgerHistory::select('journals.*')->join('journals','journals.id','=','general_ledger_history.journal_id')->where(['is_revisi'=>$history->is_revisi,'general_ledger_history.general_ledger_id'=>$data->id,'journals.coa_id'=>$coa->id])->count()==0) @continue @endif
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
                            @foreach(\App\Models\GeneralLedgerHistory::select('journals.*')->join('journals','journals.id','=','general_ledger_history.journal_id')->where(['is_revisi'=>$history->is_revisi,'general_ledger_history.general_ledger_id'=>$data->id,'journals.coa_id'=>$coa->id])->get() as $journal)
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
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
