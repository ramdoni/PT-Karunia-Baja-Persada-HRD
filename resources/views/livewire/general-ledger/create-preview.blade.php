<div class="modal-content">
    <form wire:submit.prevent="submit">
        <div class="modal-header row">
            <h5 class="modal-title ml-3" id="exampleModalLabel"><i class="fa fa-plus"></i> Submit General Ledger <span class="text-danger">{{general_ledger_number()}}</span></h5>
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
                     @if(\App\Models\Journal::where(['coa_id'=>$coa->id,'status_general_ledger'=>1])->count()==0) @continue @endif
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
                                <th colspan="5" class="text-right">Total {{$coa->name}}</th>
                                <th class="text-right">{{format_idr($total_debit)}}</th>
                                <th class="text-right">{{format_idr($total_kredit)}}</th>
                                <th class="text-right">{{format_idr($total_saldo)}}</th>
                            </tr>
                        </thead>
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
                <label>Month</label>
                <select class="form-control" wire:model="month">
                    <option value=""> --- Select --- </option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                @error("month")
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-2">
                <label>Year</label>
                <select class="form-control" wire:model="year">
                    <option value=""> --- Select --- </option>
                    @for($tahun=date('Y');$tahun<date('Year',strtotime("+3 Year"));$tahun++)
                    <option>{{$tahun}}</option>
                    @endfor
                </select>
                @error("year")
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-info mt-4"><i class="fa fa-save"></i> Submit General Ledger</button>
                <span class="text-danger">{{ $message_error }}</span>
            </div>
        </div>
    </form>
</div>