@section('title', 'Syariah')
@section('parentPageTitle', 'General Ledger')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-2">
                        <select class="form-control" wire:model="coa_group_id">
                            <option value=""> -- All Group -- </option>
                            @foreach(\App\Models\CoaGroup::orderBy('name',"ASC")->get() as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control journal_date" placeholder="Journal Date" />
                    </div>
                    <div class="col-md-8">
                        <button type="button" wire:click="downloadExcel" class="btn btn-info"><i class="fa fa-download"></i> Download Report</button>
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
                        @foreach($coas as $coa)
                            <tr>
                                <td>{{$coa->code}}</td>
                                <th colspan="7">{{$coa->name}}</th>
                            </tr>
                            <tr>
                                <td></td>
                                <th colspan="6">Saldo Awal</th>
                                <td>{{format_idr($coa->opening_balance)}}</td>
                            </tr>
                            @foreach(\App\Models\Journal::where('coa_id',$coa->id)->get() as $journal)
                                <tr>
                                    <td></td>
                                    <td>{{$journal->no_voucher}}</td>
                                    <td>{{date('d-M-Y',strtotime($journal->date_journal))}}</td>
                                    <td>{{isset($journal->coa->name)?$journal->coa->name : ''}}</td>
                                    <td>{{$journal->description}}</td>
                                    <td class="text-right">{{format_idr($journal->debit)}}</td>
                                    <td class="text-right">{{format_idr($journal->kredit)}}</td>
                                    <td class="text-right">{{format_idr($journal->saldo)}}</td>
                                </tr>
                            @endforeach
                            @if(\App\Models\Journal::where('coa_id',$coa->id)->count()==0)
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
                            <tr>
                                <td colspan="9" class="py-2" style="border-left:0;border-right:0;"></td>
                            </tr>
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
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('after-scripts')
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <script>
        $('.journal_date').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("from_date", start.format('YYYY-MM-DD'));
            @this.set("to_date", end.format('YYYY-MM-DD'));
            $('.journal_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

    </script>
    {{-- <script>
        $('body').addClass('layout-fullwidth');
		$(this).find(".fa").toggleClass('fa-arrow-left fa-arrow-right');
    </script> --}}
    @endpush
</div>
