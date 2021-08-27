<div class="modal-content">
    <form wire:submit.prevent="submit">
        <div class="modal-header row">
            <h5 class="modal-title ml-3" id="exampleModalLabel"><i class="fa fa-plus"></i> Submit / Preview General Ledger</h5>
            <span wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover m-b-0 c_list table-bordered">

                    @foreach($coas as $coa)
                        @if(\App\Models\Journal::where(['coa_id'=>$coa->id,'status_general_ledger'=>1])->count()==0) @continue @endif
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
                        <tr>
                            <td>{{$coa->code}}</td>
                            <th colspan="7">{{$coa->name}}</th>
                        </tr>
                        <tr>
                            <td></td>
                            <th colspan="6">Saldo Awal</th>
                            <td class="text-right">{{format_idr($coa->opening_balance)}}</td>
                        </tr>
                        @php($total_debit=0)
                        @php($total_kredit=0)
                        @php($total_saldo=$coa->opening_balance)
                        @foreach(\App\Models\Journal::where(['coa_id'=>$coa->id,'status_general_ledger'=>1])->get() as $journal)
                            <tr>
                                <td class="text-center"><a href="javascript:;" wire:click="delete({{$journal->id}})" class="text-danger"><i class="fa fa-trash"></i></a></td>
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
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        {{-- <tr>
                            <td colspan="9" class="py-2" style="border-left:0;border-right:0;"></td>
                        </tr> --}}
                        {{-- <thead style="background: #eee;">
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
                        </thead> --}}
                    @endforeach
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <div>
                <span wire:loading>
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">{{ __('Loading...') }}</span>
                </span>
            </div>
            <div class="col-md-2">
                <label>Submit Date <small>( default today )</small></label>
                <input type="date" class="form-control" placeholder="Submit Date" wire:model="submit_date" />
            </div>
            <button type="submit" class="btn btn-info mt-4"><i class="fa fa-save"></i> Submit General Ledger</button>
        </div>
    </form>
</div>