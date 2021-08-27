@section('title', 'Premium Receivable')
@section('parentPageTitle', 'Income')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Total</h6>
                            <span>{{ format_idr($total) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Receive</h6>
                            <span>{{ format_idr($received) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-warning">Outstanding</h6>
                            <span>{{ format_idr($outstanding) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-yellow m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
                    <div class="col-md-3">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="unit">
                            <option value=""> --- Unit --- </option>
                            <option value="1">[K] Konven </option>
                            <option value="2">[S] Syariah</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="status">
                            <option value=""> --- Status --- </option>
                            <option value="1"> Unpaid </option>
                            <option value="2"> Paid</option>
                            <option value="3"> Outstanding</option>
                            <option value="4"> Premi tidak tertagih</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control payment_date" placeholder="Payment Date" />
                    </div>
                    <div class="col-md-2">
                        <a href="javascript:;" wire:click="downloadExcel" class="btn btn-info"><i
                                class="fa fa-download"></i> Download</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Status</th>
                                <th>No Voucher</th>
                                <th>Payment Date</th>
                                <th>Voucher Date</th>
                                <th>Reference Date</th>
                                <th>Aging</th>
                                <th>Due Date</th>
                                <th>Debit Note / Kwitansi</th>
                                <th>Policy Number / Policy Holder</th>
                                <th>Cancelation</th>
                                <th>Endorsement</th>
                                <th>Total</th>
                                <th>From Bank Account</th>
                                <th>To Bank Account</th>
                                <th>Outstanding Balance</th>
                                <th>Bank Charges</th>
                                <th>Payment Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($num = $data->firstItem())
                                @foreach ($data as $k => $item)
                                    <tr>
                                        <td style="width: 50px;">{{ $num }}</td>
                                        <td>
                                            <a href="{{ route('income.premium-receivable.detail', ['id' => $item->id,'page'=>$page,'keyword'=>$keyword,'unit'=>$unit,'status'=>$status,'payment_date_from'=>$payment_date_from,'payment_date_to'=>$payment_date_to])}}">{!! status_income($item->status) !!}</a>
                                        </td>
                                        <td><a href="{{ route('income.premium-receivable.detail', ['id' => $item->id,'page'=>$page,'keyword'=>$keyword,'unit'=>$unit,'status'=>$status,'payment_date_from'=>$payment_date_from,'payment_date_to'=>$payment_date_to])}}">{!! no_voucher($item) !!}</a></td>
                                        <td>{{ $item->payment_date ? date('d M Y', strtotime($item->payment_date)) : '-' }}</td>
                                        <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ date('d M Y', strtotime($item->reference_date)) }}</td>
                                        <td>{{ calculate_aging($item->due_date) }}</td>
                                        <td>{{ $item->due_date ? date('d M Y', strtotime($item->due_date)) : '' }}</td>
                                        <td class="text-info" title="Source  From : {{$item->transaction_table}}">{{ $item->reference_no ? $item->reference_no : '-' }}</td>
                                        <td>{{ $item->client ? $item->client : '-' }}</td>
                                        <td>
                                            @if ($item->type == 1)
                                                {{ isset($item->cancelation_konven) ? format_idr($item->cancelation_konven->sum('nominal')) : 0 }}
                                            @else
                                                {{ isset($item->cancelation_syariah) ? format_idr($item->cancelation_syariah->sum('nominal')) : 0 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->type == 1)
                                                {{ isset($item->endorsemement_konven) ? format_idr($item->endorsement_konven->sum('nominal')) : 0 }}
                                            @else
                                                {{ isset($item->endorsemement_syariah) ? format_idr($item->endorsement_syariah->sum('nominal')) : 0 }}
                                            @endif
                                        </td>
                                        <td>{{ isset($item->nominal) ? format_idr($item->nominal) : '-' }}</td>
                                        <td>{{ isset($item->from_bank_account->no_rekening) ? $item->from_bank_account->no_rekening . '- ' . $item->from_bank_account->bank . ' an ' . $item->from_bank_account->owner : '-' }}
                                        </td>
                                        <td>{{ isset($item->bank_account->no_rekening) ? $item->bank_account->no_rekening . ' - ' . $item->bank_account->bank . ' an ' . $item->bank_account->owner : '-' }}
                                        </td>
                                        <td>{{ isset($item->outstanding_balance) ? format_idr($item->outstanding_balance) : '-' }}
                                        </td>
                                        <td>{{ isset($item->bank_charges) ? format_idr($item->bank_charges) : '-' }}</td>
                                        <td>{{ isset($item->payment_amount) ? format_idr($item->payment_amount) : '-' }}
                                        </td>
                                    </tr>
                                    @php($num++)
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br />
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
        @push('after-scripts')
            <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
            <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
            <script>
                Livewire.on('update-url',(url)=>{
                    setTimeout(function(){
                        window.history.pushState('', '', url);
                    });
                })
                $('.payment_date').daterangepicker({
                    opens: 'left'
                }, function(start, end, label) {
                    @this.set("payment_date_from", start.format('YYYY-MM-DD'));
                    @this.set("payment_date_to", end.format('YYYY-MM-DD'));
                });

            </script>
        @endpush
