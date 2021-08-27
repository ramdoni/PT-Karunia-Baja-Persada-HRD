@section('title', 'Recovery Claim')
@section('parentPageTitle', 'Income')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <table class="table pl-0 mb-0 table-striped table-nowrap">
                        <tr>
                            <th style="widht: 40%;">{{ __('Voucher Number') }}</th>
                            <td>{!!no_voucher($data)!!}</td>
                        </tr>
                        <tr>
                            <th style="width:35%">{{ __('Claim Payable') }}</th>
                            <td>
                                @if(isset($data->expense->no_voucher))
                                    <p><a href="{{route('expense.claim.detail',$data->transaction_id)}}" target="_blank">{!!no_voucher($data->expense)!!}<br />{{$data->expense->recipient}}</a></p>
                                @endif
                                @if($list_claim)
                                    @foreach($list_claim as $item)
                                    <p><a href="{{route('expense.claim.detail',$data->transaction_id)}}" target="_blank">{!!no_voucher($data->expense)!!}<br />{{$data->expense->recipient}}</a></p>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Reference Date') }}</th>
                            <td>{{date('d F Y',strtotime($data->reference_date))}}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Reference No') }}</th>
                            <td>{{$data->reference_no}}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Payment Amount')}}</th>
                            <td>{{format_idr($data->payment_amount)}}</td>
                        </tr>
                        <tr>
                            <th>Premium Deposit</th>
                            <td>
                                @if($titipan_premi)
                                    @foreach($titipan_premi as $item)
                                    @php($titipan = $item->titipan)
                                    <p>
                                        No Voucher : <a href="{{route('income.titipan-premi.detail',$titipan->id)}}" target="_blank">{{$titipan->no_voucher}}</a> <br />
                                        {{isset($titipan->from_bank_account->no_rekening) ? $titipan->from_bank_account->no_rekening .'- '.$titipan->from_bank_account->bank.' an '. $titipan->from_bank_account->owner : '-'}} <br />
                                         <strong>{{format_idr($item->nominal)}}</strong>
                                    </p>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('From Bank Account')}}</th>
                            <td>{!!isset($data->from_bank_account->no_rekening) ? $data->from_bank_account->no_rekening .' - '.$data->from_bank_account->bank.' an '.$data->from_bank_account->owner : ''!!}</td>
                        </tr>
                        <tr>
                            <th>{{__('To Bank Account')}}</th>
                            <td>{{isset($data->bank_account->no_rekening) ? $data->bank_account->no_rekening .' - '.$data->bank_account->bank.' an '. $data->bank_account->owner : '-'}}</td>
                        </tr>
                        <tr>
                            <th>{{__('Payment Date')}}*<small>{{__('Default today')}}</small></th>
                            <td>{{date('d F Y',strtotime($data->payment_date))}}</td>
                        </tr>
                        
                        <tr>
                            <th>{{__('Bank Charges')}}</th>
                            <td>{{ format_idr($data->bank_charges) }}</td>
                        </tr>
                        <tr>
                            <th>{{__('Description')}}</th>
                            <td>{{$data->description}}</td>
                        </tr>
                    </table>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <div wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="body">
                <div class="table-responsive" style="max-height: 532px;">
                    <table class="table table-striped table-hover m-b-0 c_list table-nowrap">
                        <tr>
                            <th>No Voucher</th>
                            <td>:</td>
                            <td>{!!isset($expense->no_voucher) ? no_voucher($expense) : ''!!}</td>
                        </tr>
                        <tr>
                            <th>Payment Date</th>
                            <td>:</td>
                            <td>{!!isset($expense->payment_date) ? date('d-M-Y',strtotime($expense->payment_date)) : ''!!}</td>
                        </tr>
                        <tr>
                            <th>Voucher Date</th>
                            <td>:</td>
                            <td>{!!isset($expense->created_at) ? date('d-M-Y',strtotime($expense->created_at)) : ''!!}</td>
                        </tr>
                        <tr>
                            <th>Debit Note / Kwitansi</th>
                            <td>:</td>
                            <td>{!!isset($expense->reference_no) ? $expense->reference_no : ''!!}</td>
                        </tr>
                        <tr>
                            <th>Policy Number / Policy Holder</th>
                            <td>:</td>
                            <td>{!!isset($expense->recipient) ? $expense->recipient : ''!!}</td>
                        </tr>
                        <tr>
                            <th>From Bank Account</th>
                            <td>:</td>
                            <td>{!!isset($expense->from_bank_account->no_rekening) ? $expense->from_bank_account->no_rekening .' - '.$expense->from_bank_account->bank.' an '.$expense->from_bank_account->owner : ''!!}</td>
                        </tr>
                        <tr>
                            <th>To Bank Account</th>
                            <td>:</td>
                            <td>{!!isset($expense->bank_account->no_rekening) ? $expense->bank_account->no_rekening .' - '.$expense->bank_account->bank.' an '.$expense->bank_account->owner : ''!!}</td>
                        </tr>
                        <tr>
                            <th>Bank Charges</th>
                            <td>:</td>
                            <td>{!!isset($expense->bank_charges) ? format_idr($expense->bank_charges) : ''!!}</td>
                        </tr>
                        <tr>
                            <th>Payment Amount</th>
                            <td>:</td>
                            <td>{!!isset($expense->payment_amount) ? format_idr($expense->payment_amount) : ''!!}</td>
                        </tr>
                    </table>
    
                    @if($list_claim)
                    @foreach($list_claim as $claim)
                        <hr />
                        <table class="table table-striped table-hover m-b-0 c_list table-nowrap">
                            <tr>
                                <th>No Voucher</th>
                                <td>:</td>
                                <td>{!!isset($expense->no_voucher) ? no_voucher($expense) : ''!!}</td>
                            </tr>
                            <tr>
                                <th>Payment Date</th>
                                <td>:</td>
                                <td>{!!isset($expense->payment_date) ? date('d-M-Y',strtotime($expense->payment_date)) : ''!!}</td>
                            </tr>
                            <tr>
                                <th>Voucher Date</th>
                                <td>:</td>
                                <td>{!!isset($expense->created_at) ? date('d-M-Y',strtotime($expense->created_at)) : ''!!}</td>
                            </tr>
                            <tr>
                                <th>Debit Note / Kwitansi</th>
                                <td>:</td>
                                <td>{!!isset($expense->reference_no) ? $expense->reference_no : ''!!}</td>
                            </tr>
                            <tr>
                                <th>Policy Number / Policy Holder</th>
                                <td>:</td>
                                <td>{!!isset($expense->recipient) ? $expense->recipient : ''!!}</td>
                            </tr>
                            <tr>
                                <th>From Bank Account</th>
                                <td>:</td>
                                <td>{!!isset($expense->from_bank_account->no_rekening) ? $expense->from_bank_account->no_rekening .' - '.$expense->from_bank_account->bank.' an '.$expense->from_bank_account->owner : ''!!}</td>
                            </tr>
                            <tr>
                                <th>To Bank Account</th>
                                <td>:</td>
                                <td>{!!isset($expense->bank_account->no_rekening) ? $expense->bank_account->no_rekening .' - '.$expense->bank_account->bank.' an '.$expense->bank_account->owner : ''!!}</td>
                            </tr>
                            <tr>
                                <th>Bank Charges</th>
                                <td>:</td>
                                <td>{!!isset($expense->bank_charges) ? format_idr($expense->bank_charges) : ''!!}</td>
                            </tr>
                            <tr>
                                <th>Payment Amount</th>
                                <td>:</td>
                                <td>{!!isset($expense->payment_amount) ? format_idr($expense->payment_amount) : ''!!}</td>
                            </tr>
                        </table>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;padding-left:10px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
@endpush
@section('page-script')
    Livewire.on('init-form', () =>{
        setTimeout(function(){
            init_form();
        },1500);
    });
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        
        select__2 = $('.select_expense_id').select2();
        $('.select_expense_id').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('.select_expense_id').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);

        select_from_bank = $('.from_bank_account').select2();
        $('.from_bank_account').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__from_bank = $('.from_bank_account').find(':selected').val();
        if(select_from_bank !="") select_from_bank.val(selected__from_bank);
        
        $('.select_to_bank').each(function(){
            select_to_bank = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected_to_bank = $(this).find(':selected').val();
            if(selected_to_bank !="") select_to_bank.val(selected_to_bank);
        });
    }
    setTimeout(function(){
        init_form()
    })
@endsection