@section('title', 'Commision Payable')
@section('parentPageTitle', 'Expense')
<div class="clearfix row">
    <div class="col-md-5">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="submit">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <p>{{ __('Voucher Number') }} : <strong class="text-success">{{$no_voucher}}</strong></p>
                            </div>
                            <div class="col-md-6 text-right">
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
                        <hr />
                    </div>
                    <div class="form-group">
                        <label>{{ __('No Polis') }}</label>
                        <div wire:ignore>
                            <select class="form-control select_no_polis" wire:model="no_polis" id="no_polis">
                                <option value=""> --- Select --- </option>
                                @foreach(\App\Models\Policy::orderBy('pemegang_polis','ASC')->get() as $item)
                                <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->pemegang_polis}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('no_polis')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Reference No') }}</label>
                        <input type="text" class="form-control" wire:model="reference_no" />
                        @error('reference_no')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <hr />
                    @foreach($payments as $k => $payment)
                    <div wire:key="payment-section{{$k}}">
                        <div class="form-group">
                            <select class="form-control" wire:model.lazy="transaction_type.{{$k}}" required>
                                <option value=""> --- {{__('Payment Type')}} --- </option>
                                <option>Fee Base</option>
                                <option>Maintenance</option>
                                <option>Admin Agency</option>
                                <option>Agen Penutup</option>
                                <option>Operasional Agency</option>
                                <option>Handling Fee Broker</option>
                                <option>Referal Fee</option>
                            </select>
                            @error('transaction_type[{{$k}}]')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group" wire:ignore>
                            <input type="text" class="form-control" wire:model="nama.{{$k}}" placeholder="Name" />
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" wire:model="bank.{{$k}}" placeholder="Bank" />
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" class="form-control" wire:model="no_rekening.{{$k}}" placeholder="Account Number" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control format_number" required wire:model="payment_amount.{{$k}}" placeholder="{{ __('Payment Amount') }}">
                                @error('payment_amount.{{$k}}')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" wire:model="payment_date.{{$k}}" required onfocus="(this.type='date')" placeholder="{{ __('Payment Date') }}">
                            </div>        
                        </div>
                        <a href="javascript:;" wire:click="delete_payment({{$k}})" class="text-danger"><i class="fa fa-trash"></i> Delete</a>
                        <hr />
                    </div>
                    @endforeach
                    <a href="javascript:void(0);" wire:click="add_payment"><i class="fa fa-plus"></i> Add Payment</a>
                    <div class="form-group">
                        <select class="form-control" wire:model="from_bank_account_id">
                            <option value=""> --- {{ __('From Bank Account') }} --- </option>
                            @foreach (\App\Models\BankAccount::where('is_client',0)->orderBy('owner','ASC')->get() as $bank)
                                <option value="{{ $bank->id}}">{{ $bank->owner }} - {{ $bank->no_rekening}} {{ $bank->bank}}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary" {{!$is_submit?'disabled':''}}><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                    <button type="button" class="ml-3 btn btn-info float-right" wire:click="saveAsDraft"><i class="fa fa-save"></i> {{ __('Save as Draft') }}</button>
                    <div wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="body">
                <table class="table table-striped table-hover m-b-0 c_list table-nowrap">
                    <tr>
                        <th>No Polis</th>
                        <td> :</td>
                        <td>{{isset($data->no_polis) ? $data->no_polis : ''}} 
                            @if($data)
                                @if($data->type==1)
                                    <span class="badge badge-info">Konven</span>
                                @else
                                    <span class="badge badge-warning">Syariah</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Pemegang Polis</th>
                        <td>:</td>
                        <td>{{isset($data->pemegang_polis) ? $data->pemegang_polis : ''}}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td>{{isset($data->alamat) ? $data->alamat : ''}}</td>
                    </tr>
                    <tr>
                        <th>Produk</th>
                        <td>:</td>
                        <td>{{isset($data->produk) ? $data->produk : ''}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Premium Receivable</h6>
                        <hr />
                        <div class="table-responsive" style="max-height: 700px;">
                            <table class="table table-striped table-hover m-b-0 c_list table-nowrap">
                                <tr>
                                    <th>No</th>                                    
                                    <th>Status</th>                                    
                                    <th>No Voucher</th>                                    
                                    <th>Payment Date</th>                                    
                                    <th>Voucher Date</th>                                    
                                    <th>Reference Date</th>
                                    <th>Aging</th>
                                    <th>Debit Note / Kwitansi</th>
                                    <th>Cancelation</th>                   
                                    <th>Endorsement</th>                   
                                    <th>Total</th>                                               
                                    <th>From Bank Account</th>
                                    <th>To Bank Account</th>
                                    <th>Outstanding Balance</th>
                                    <th>Bank Charges</th>
                                    <th>Payment Amount</th>
                                </tr>
                            @if($premium_receivable)
                                @foreach($premium_receivable as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td><a href="{{route('income.premium-receivable.detail',['id'=>$item->id])}}">{!!status_income($item->status)!!}</a></td>
                                    <td>
                                        <a href="{{route('income.premium-receivable.detail',['id'=>$item->id])}}">{{$item->no_voucher}}</a>
                                        @if($item->type==1)
                                        <span class="badge badge-danger" title="Konven">K</span>
                                        @else
                                        <span class="badge badge-info" title="Syariah">S</span>
                                        @endif
                                    </td>
                                    <td>{{$item->payment_date?date('d M Y', strtotime($item->payment_date)):'-'}}</td>
                                    <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td>{{date('d M Y', strtotime($item->reference_date))}}</td>
                                    <td>{{calculate_aging($item->reference_date)}}</td>
                                    <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                                    <td>{{ isset($item->cancelation)?format_idr($item->total_cancelation->sum('nominal')):0 }}</td>
                                    <td>{{ isset($item->endorsemement)?format_idr($item->endorsement->sum('nominal')):0 }}</td>
                                    <td>{{isset($item->nominal) ? format_idr($item->nominal) : '-'}}</td>
                                    <td>{{isset($item->from_bank_account->no_rekening) ? $item->from_bank_account->no_rekening .'- '.$item->from_bank_account->bank.' an '. $item->from_bank_account->owner : '-'}}</td>
                                    <td>{{isset($item->bank_account->no_rekening) ? $item->bank_account->no_rekening .' - '.$item->bank_account->bank.' an '. $item->bank_account->owner : '-'}}</td>
                                    <td>{{isset($item->outstanding_balance) ? format_idr($item->outstanding_balance) : '-'}}</td>
                                    <td>{{isset($item->bank_charges) ? format_idr($item->bank_charges) : '-'}}</td>
                                    <td>{{isset($item->payment_amount) ? format_idr($item->payment_amount) : '-'}}</td>
                                </tr>
                            @endforeach
                            @endif
                            </table>
                        </div>
                    </div>
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
        console.log('init form');
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        select__2 = $('.select_no_polis').select2();
        $('.select_no_polis').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('.select_no_polis').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
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