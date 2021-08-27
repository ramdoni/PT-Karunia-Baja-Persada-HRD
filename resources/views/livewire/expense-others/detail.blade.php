@section('title', 'Others Expense')
@section('parentPageTitle', 'Expense')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save('Submit')">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Voucher Number') }} : <span class="text-success">{{$no_voucher}}</span></label>
                        </div>
                        <div class="form-group col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Recipient') }}</label>
                                <input type="text" class="form-control" wire:model="recipient" />
                                @error('recipient')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference Type') }}</label>
                                <select class="form-control" wire:model="reference_type">
                                    <option value=""> --- Select --- </option>
                                    <option>Invoice</option>
                                    <option>Debit Note</option>
                                    <option>Credit Note</option>
                                    <option>Othes</option>
                                </select>
                                @error('reference_type')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference No') }}</label>
                                <input type="text" class="form-control" wire:model="reference_no" />
                                @error('reference_no')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Reference Date') }} *<small>{{__('Default today')}}</small></label>
                                <input type="date" class="form-control" wire:model="reference_date" />
                                @error('reference_date')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Amount (Rp)') }}</label>
                                <input type="text" class="form-control format_number" wire:ignore wire:model="nominal" />
                                @error('nominal')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('From Bank Account') }}</label>
                                <select class="form-control" wire:model="from_bank_account_id">
                                    <option value=""> --- Select --- </option>
                                    @foreach (\App\Models\BankAccount::where('is_client',0)->orderBy('bank','ASC')->get() as $bank)
                                        <option value="{{ $bank->id}}">{{ $bank->no_rekening}} {{ $bank->bank}} an {{$bank->owner}}</option>
                                    @endforeach
                                </select>
                                @error('from_bank_account_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('To Bank Account') }}</label><a href="javascript:;" data-toggle="modal" data-target="#modal_add_bank" class="ml-3"><i class="fa fa-plus"></i> Add Bank</a>
                                <select class="form-control to_bank_account" id="to_bank_account_id" wire:model="to_bank_account_id">
                                    <option value=""> --- Select --- </option>
                                    @foreach (\App\Models\BankAccount::where('is_client',2)->orderBy('bank','ASC')->get() as $bank)
                                        <option value="{{ $bank->id}}">{{ $bank->no_rekening}} {{ $bank->bank}} an {{$bank->owner}}</option>
                                    @endforeach
                                </select>
                                @error('to_bank_account_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Payment Date') }} *<small>{{__('Default today')}}</small></label>
                                <input type="date" class="form-control" {{$is_readonly?'disabled':''}} wire:model="payment_date" />
                                @error('payment_date')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <textarea class="form-control" wire:model="description" placeholder="Description"></textarea>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Payment Amount') }} <br/> <span class="btn btn-outline-success">{{format_idr($payment_amount)}}</span></label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ __('Outstanding Balance') }} <br/> <span class="btn btn-outline-danger">{{$outstanding_balance}}</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    @foreach($add_payment as $k => $item)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Transaction Type') }}</label>
                                <select class="form-control select_transaction_type" id="add_payment_transaction_type.{{$k}}" wire:model="add_payment_transaction_type.{{$k}}">
                                    <option value=""> --- Select --- </option>
                                    @foreach(\App\Models\Coa::where('is_others_expense',1)->groupBy('coa_group_id')->get() as $group)
                                        <optgroup label="{{isset($group->group->name) ? $group->group->name : ''}}">
                                            @foreach(\App\Models\Coa::where(['is_others_expense'=>1,'coa_group_id'=>$group->coa_group_id])->get() as $coa)
                                                <option value="{{$coa->id}}">{{$coa->name}} ({{$coa->code}})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('add_payment_transaction_type.'.$k)
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                                <a href="javascript:;" title="Delete" wire:click="delete({{$k}})" class="text-danger"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Payment Amount (Rp)') }}</label>
                                <input type="text" {{$is_readonly?'disabled':''}} class="form-control format_number" wire:ignore wire:model="add_payment_amount.{{$k}}" />
                                @error('payment_amount')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <input type="text" class="form-control" wire:model="add_payment_description.{{$k}}" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @foreach($add_payment_temp as $k => $item)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ __('Transaction Type') }}</label>
                                <select class="form-control select_transaction_type" id="add_payment_transaction_type_temp.{{$k}}" wire:model="add_payment_transaction_type_temp.{{$k}}">
                                    <option value=""> --- Select --- </option>
                                    @foreach(\App\Models\Coa::where('is_others_expense',1)->get() as $coa)
                                    <option value="{{$coa->id}}">{{$coa->name}}</option>
                                    @endforeach
                                </select>
                                @error('add_payment_transaction_type_temp.'.$k)
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                                <a href="javascript:;" title="Delete" wire:click="deleteTemp({{$k}})" class="text-danger"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{ __('Payment Amount (Rp)') }}</label>
                                <input type="text" {{$is_readonly?'disabled':''}} class="form-control format_number" wire:ignore wire:model="add_payment_amount_temp.{{$k}}" />
                                @error("add_payment_amount_temp.{$k}")
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <input type="text" class="form-control" wire:model="add_payment_description_temp.{{$k}}" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <a href="javascript:;" wire:click="addPayment"><i class="fa fa-plus"></i> Payment</a>
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    @if($outstanding_balance==0 and $payment_amount!=0)
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                    @endif
                    <button type="button" wire:click="save('Draft')" class="ml-3 btn btn-info"><i class="fa fa-save"></i> {{ __('Save as Draft') }}</button>
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:expense-others.add-bank />
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
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<script>
    Livewire.on('emit-add-bank',()=>{
        $("#modal_add_bank").modal("hide");    
    });
    Livewire.on('init-form', () =>{
        init_form();
    });
    function init_form(){
        $(".select_transaction_type").each(function(){
            select_transaction_type = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected_transaction_type = $(this).find(':selected').val();
            if(selected_transaction_type !="") select_transaction_type.val(selected_transaction_type);
        });

        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        
        select__2 = $('.to_bank_account').select2();
        $('.to_bank_account').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('.to_bank_account').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
    }   
    setTimeout(function(){
        init_form()
    })
</script>
@endpush