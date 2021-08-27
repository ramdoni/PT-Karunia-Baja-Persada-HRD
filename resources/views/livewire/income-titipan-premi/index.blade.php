@section('title', 'Premium Deposit')
@section('parentPageTitle', 'Income')
    <div class="clearfix row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-0">
                        <div class="body py-2">
                            <div class="number">
                                <h6 class="text-info">Total</h6>
                                <span>{{ format_idr($total) }}</span>
                            </div>
                        </div>
                        <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                            <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-0">
                        <div class="body py-2">
                            <div class="number">
                                <h6 class="text-success">Teralokasi</h6>
                                <span>{{ format_idr($teralokasi) }}</span>
                            </div>
                        </div>
                        <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                            <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card  mb-0">
                        <div class="body py-2">
                            <div class="number">
                                <h6 class="text-warning">Balance</h6>
                                <span>{{ format_idr($balance) }}</span>
                            </div>
                        </div>
                        <div class="progress progress-xs progress-transparent custom-color-yellow  m-b-0">
                            <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="mb-2 row">
                        <div class="col-md-2">
                            <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" wire:model="status">
                                <option value=""> --- Status --- </option>
                                <option value="1"> Outstanding </option>
                                <option value="2"> Complete</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" wire:model="to_bank_account_id">
                                <option value=""> --- To Bank Account --- </option>
                                @foreach (\App\Models\BankAccount::where('is_client', 0)->get() as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank }} - {{ $bank->no_rekening }} -
                                        {{ $bank->owner }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" wire:ignore class="form-control payment_date" placeholder="Payment Date" />
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('income.titipan-premi.insert') }}" class="btn btn-info"><i
                                    class="fa fa-plus"></i> Premium Deposit</a>
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
                                    {{-- <th>Reference Date</th> --}}
                                    <th>Reference No</th>
                                    <th>From Bank Account</th>
                                    <th>To Bank Account</th>
                                    <th>Payment Amount</th>
                                    <th>Premium Receive</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $k => $item)
                                    <tr>
                                        <td style="width: 50px;">{{ $k + 1 }}</td>
                                        <td>
                                            <a href="{{ route('income.titipan-premi.detail', ['id' => $item->id]) }}">
                                                @if ($item->status == 1)
                                                    <span class="badge badge-warning">Outstanding</span>
                                                @endif
                                                @if ($item->status == 2)
                                                    <span class="badge badge-success">Completed</span>
                                                @endif
                                            </a>
                                        </td>
                                        <td><a
                                                href="{{ route('income.titipan-premi.detail', ['id' => $item->id]) }}">{{ $item->no_voucher }}</a>
                                        </td>
                                        <td>{{ $item->payment_date ? date('d M Y', strtotime($item->payment_date)) : '-' }}
                                        </td>
                                        <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                        {{-- <td>{{ date('d M Y', strtotime($item->reference_date)) }}</td> --}}
                                        <td>{{ $item->reference_no ? $item->reference_no : '-' }}</td>
                                        <td>{{ isset($item->from_bank_account->no_rekening) ? $item->from_bank_account->no_rekening . '- ' . $item->from_bank_account->bank . ' an ' . $item->from_bank_account->owner : '-' }}
                                        </td>
                                        <td>{{ isset($item->bank_account->no_rekening) ? $item->bank_account->no_rekening . ' - ' . $item->bank_account->bank . ' an ' . $item->bank_account->owner : '-' }}
                                        </td>
                                        <td>{{ isset($item->nominal) ? format_idr($item->nominal) : '-' }}</td>
                                        <td>{{ format_idr($item->payment_amount) }}</td>
                                        <td>{{ format_idr($item->outstanding_balance) }}</td>
                                    </tr>
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
            $('.payment_date').daterangepicker({
                locale: {
                    cancelLabel: 'Clear'
                },
                autoUpdateInput: false,
                opens: 'left'
            }, function(start, end, label) {
                @this.set("payment_date_from", start.format('YYYY-MM-DD'));
                @this.set("payment_date_to", end.format('YYYY-MM-DD'));

                $('.payment_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
            });

        </script>
    @endpush
