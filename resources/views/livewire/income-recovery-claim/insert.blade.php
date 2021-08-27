@section('title', 'Recovery Claim')
@section('parentPageTitle', 'Income')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <table class="table pl-0 mb-0 table-striped">
                        <tr>
                            <th>{{ __('Voucher Number') }}</th>
                            <td>
                                {{$no_voucher}}
                                <div class="float-right">
                                    <label class="fancy-radio">
                                        <input type="radio" value="1" wire:model="type" /> 
                                        <span><i></i>Konven</span>
                                    </label> 
                                    <label class="fancy-radio">
                                        <input type="radio" value="2" wire:model="type" />
                                        <span><i></i>Syariah</span>
                                    </label> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="width:35%">{{ __('Claim Payable') }}</th>
                            <td>
                                <div wire:ignore>
                                    <select class="form-control select_expense_id" wire:model="expense_id" id="expense_id">
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\Expenses::where('reference_type','Claim')->get() as $item)
                                        <option value="{{$item->id}}">{{$item->no_voucher}} / {{$item->recipient}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('no_polis')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                                @foreach($add_claim_payables as $k =>$i)
                                <div wire:ignore class="mt-2">
                                    <select class="form-control select_expense_id" wire:model="add_expense_id.{{$k}}" id="add_expense_id.{{$k}}">
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\Expenses::where('reference_type','Claim')->get() as $item)
                                        <option value="{{$item->id}}">{{$item->no_voucher}} / {{$item->recipient}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach
                                @if(!$is_readonly)
                                <a href="javascript:;" wire:click="add_claim_payable"><i class="fa fa-plus"></i> Add Claim Payable</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Reference Date') }}</th>
                            <td>
                                <input type="date" class="form-control col-md-6" wire:model="reference_date" />
                                @error('reference_date')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Reference No') }}</th>
                            <td>
                                <input type="text" class="form-control" wire:model="reference_no" />
                                @error('reference_no')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Payment Amount')}}</th>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control format_number text-right" wire:model="payment_amount" />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" {{$is_readonly?'disabled':''}} class="form-control format_number text-right" placeholder="{{__('Bank Charges')}}" wire:model="bank_charges" />
                                    </div>
                                </div>
                            </td>
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
                                        @if(!$is_readonly)
                                         <a href="javascript:void(0)" wire:click="clearTitipanPremi" class="text-danger"><i class="fa fa-trash"></i></a>
                                        @endif
                                    </p>
                                    @endforeach
                                @endif

                                @if($temp_titipan_premi)
                                    @foreach($temp_titipan_premi as $titipan)
                                    <p>
                                        No Voucher : <a href="{{route('income.titipan-premi.detail',$titipan->id)}}" target="_blank">{{$titipan->no_voucher}}</a> <br />
                                        {{isset($titipan->from_bank_account->no_rekening) ? $titipan->from_bank_account->no_rekening .'- '.$titipan->from_bank_account->bank.' an '. $titipan->from_bank_account->owner : '-'}} <br />
                                         <strong>{{format_idr($titipan->outstanding_balance)}}</strong>
                                        @if(!$is_readonly)
                                         <a href="javascript:void(0)" wire:click="clearTitipanPremi" class="text-danger"><i class="fa fa-trash"></i></a>
                                        @endif
                                    </p>
                                    <hr />
                                    @endforeach
                                @endif
                                @if($payment_amount)
                                    @if($total_titipan_premi <= $payment_amount and !$is_readonly)
                                    <a href="javascript:void(0)" data-target="#modal_add_titipan_premi" data-toggle="modal"><i class="fa fa-plus"></i> Premium Deposit</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('From Bank Account')}}</th>
                            <td>
                                <select class="form-control from_bank_account" id="from_bank_account_id" wire:model="from_bank_account_id" {{$is_readonly?'disabled':''}}>
                                    <option value=""> --- {{__('Select')}} --- </option>
                                    @foreach (\App\Models\BankAccount::where('is_client',1)->orderBy('owner','ASC')->get() as $bank)
                                        <option value="{{ $bank->id}}">{{ $bank->owner }} - {{ $bank->no_rekening}} {{ $bank->bank}}</option>
                                    @endforeach
                                </select>
                                @error('from_bank_account_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                                <br />
                                @if(!$is_readonly)
                                <a href="#" data-toggle="modal" data-target="#modal_add_bank"><i class="fa fa-plus"></i> Add Bank</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('To Bank Account')}}</th>
                            <td>
                                <select class="form-control" wire:model="to_bank_account_id" {{$is_readonly?'disabled':''}}>
                                    <option value=""> --- {{__('Select')}} --- </option>
                                    @foreach (\App\Models\BankAccount::where('is_client',0)->orderBy('bank','ASC')->get() as $bank)
                                        <option value="{{ $bank->id}}">{{ $bank->owner }} - {{ $bank->no_rekening}} {{ $bank->bank}}</option>
                                    @endforeach
                                </select>
                                @error('bank_account_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('Payment Date')}}*<small>{{__('Default today')}}</small></th>
                            <td>
                                <input type="date" class="form-control col-md-6" {{$is_readonly?'disabled':''}} wire:model="payment_date" />
                                @error('payment_date')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('Description')}}</th>
                            <td>
                                <textarea style="height:100px;" class="form-control" wire:model="description"></textarea>
                            </td>
                        </tr>
                    </table>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary" {{!$is_submit?'disabled':''}}><i class="fa fa-save"></i> {{ __('Submit') }}</button>
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
            <div class="header pb-0">
                <h2>Claim Payable</h2>
            </div>
            <hr />
            <div class="body pt-0">
                <table class="table table-hover m-b-0 c_list table-nowrap">
                    <tr>
                        <th>No Voucher</th>
                        <td>:</td>
                        <td>{!!isset($data->no_voucher) ? no_voucher($data) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Payment Date</th>
                        <td>:</td>
                        <td>{!!isset($data->payment_date) ? date('d-M-Y',strtotime($data->payment_date)) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Voucher Date</th>
                        <td>:</td>
                        <td>{!!isset($data->created_at) ? date('d-M-Y',strtotime($data->created_at)) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Debit Note / Kwitansi</th>
                        <td>:</td>
                        <td>{!!isset($data->reference_no) ? $data->reference_no : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Policy Number / Policy Holder</th>
                        <td>:</td>
                        <td>{!!isset($data->recipient) ? $data->recipient : ''!!}</td>
                    </tr>
                    <tr>
                        <th>From Bank Account</th>
                        <td>:</td>
                        <td>{!!isset($data->from_bank_account->no_rekening) ? $data->from_bank_account->no_rekening .' - '.$data->from_bank_account->bank.' an '.$data->from_bank_account->owner : ''!!}</td>
                    </tr>
                    <tr>
                        <th>To Bank Account</th>
                        <td>:</td>
                        <td>{!!isset($data->bank_account->no_rekening) ? $data->bank_account->no_rekening .' - '.$data->bank_account->bank.' an '.$data->bank_account->owner : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Bank Charges</th>
                        <td>:</td>
                        <td>{!!isset($data->bank_charges) ? format_idr($data->bank_charges) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Payment Amount</th>
                        <td>:</td>
                        <td>{!!isset($data->payment_amount) ? format_idr($data->payment_amount) : ''!!}</td>
                    </tr>
                </table>

                @foreach($add_expense_id as $ex)
                <br />
                <table class="table table-hover m-b-0 c_list table-nowrap">
                    @php($add_expense = \App\Models\Expenses::find($ex))
                    <tr>
                        <th>No Voucher</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->no_voucher) ? no_voucher($add_expense) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Payment Date</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->payment_date) ? date('d-M-Y',strtotime($add_expense->payment_date)) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Voucher Date</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->created_at) ? date('d-M-Y',strtotime($add_expense->created_at)) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Debit Note / Kwitansi</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->reference_no) ? $add_expense->reference_no : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Policy Number / Policy Holder</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->recipient) ? $add_expense->recipient : ''!!}</td>
                    </tr>
                    <tr>
                        <th>From Bank Account</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->from_bank_account->no_rekening) ? $add_expense->from_bank_account->no_rekening .' - '.$add_expense->from_bank_account->bank.' an '.$add_expense->from_bank_account->owner : ''!!}</td>
                    </tr>
                    <tr>
                        <th>To Bank Account</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->bank_account->no_rekening) ? $add_expense->bank_account->no_rekening .' - '.$add_expense->bank_account->bank.' an '.$add_expense->bank_account->owner : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Bank Charges</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->bank_charges) ? format_idr($add_expense->bank_charges) : ''!!}</td>
                    </tr>
                    <tr>
                        <th>Payment Amount</th>
                        <td>:</td>
                        <td>{!!isset($add_expense->payment_amount) ? format_idr($add_expense->payment_amount) : ''!!}</td>
                    </tr>
                </table>
                @endforeach
            </div>
        </div>
    </div>
    <livewire:general.add-titipan-premi />
</div>
<div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:income-recovery-claim.add-bank />
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
@endpush
@section('page-script')
    Livewire.on('init-form', () =>{
        $(".modal").modal("hide");
        init_form();
    });
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        
        $('.select_expense_id').each(function(){
            var select__2 = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected__ = $(this).find(':selected').val();
            if(selected__ !="") select__2.val(selected__);
        });
        
        select_from_bank = $('.from_bank_account').select2();
        $('.from_bank_account').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__from_bank = $('.from_bank_account').find(':selected').val();
        if(select_from_bank !="") select_from_bank.val(selected__from_bank);
    }
    setTimeout(function(){
        init_form()
    })
@endsection