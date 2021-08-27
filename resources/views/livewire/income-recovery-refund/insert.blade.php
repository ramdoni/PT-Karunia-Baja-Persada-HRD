@section('title', 'Recovery Refund')
@section('parentPageTitle', 'Income')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <table class="table pl-0 mb-0 table-striped table-nowrap">
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
                            <th style="width:35%">{{ __('No Polis') }}</th>
                            <td style="width: 65%;">
                                <div wire:ignore>
                                    <select class="form-control select_no_polis" wire:model="no_polis" id="no_polis">
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\Policy::where('is_reas',1)->orderBy('pemegang_polis','ASC')->get() as $item)
                                        <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->pemegang_polis}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('no_polis')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
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
                            <th>Peserta</th>
                            <td>
                                @foreach($add_pesertas as $k => $v)
                                <div class="form-group">
                                    <input type="text" class="form-control mb-2" wire:model="no_peserta.{{$k}}" placeholder="No Peserta" />
                                    <input type="text" class="form-control" wire:model="nama_peserta.{{$k}}" placeholder="Nama Peserta" />
                                    <a href="javascript:;" class="text-danger" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash"></i> Delete</a>
                                </div>
                                <hr />
                                @endforeach
                                <a href="javascript:;" wire:click="add_peserta"><i class="fa fa-plus"></i> Add Peserta</a>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Payment Amount')}}</th>
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control format_number text-right" wire:model="payment_amount" placeholder="{{ __('Payment Amount') }}" />
                                        @error('payment_amount')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" {{$is_readonly?'disabled':''}} placeholder="{{__('Bank Charges')}}" class="form-control format_number text-right" wire:model="bank_charges" />
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
                            <td><textarea style="height:100px;" class="form-control" wire:model="description"></textarea></td>
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
                    <tr>
                        <th>Peserta</th>
                        <td>:</td>
                        <td>{{isset($data->reas->peserta) ? $data->reas->peserta : ''}}</td>
                    </tr>
                    <tr>
                        <th>Keterangan T/F</th>
                        <td>:</td>
                        <td>{{isset($data->reas->keterangan) ? $data->reas->keterangan : ''}}</td>
                    </tr>
                    <tr>
                        <th>Broker Re / Reasuradur</th>
                        <td>:</td>
                        <td>{{isset($data->reas->broker_re) ? $data->reas->broker_re : ''}}</td>
                    </tr>
                    <tr>
                        <th>Premi Reas</th>
                        <td>:</td>
                        <td>{{isset($data->reas->premi_reas_netto) ? format_idr($data->reas->premi_reas_netto) : ''}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <livewire:general.add-titipan-premi />
</div>
<div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:income-recovery-refund.add-bank />
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
@endpush
@section('page-script')
    Livewire.on('init-form', () =>{
        $(".modal").modal("hide");
        setTimeout(function(){
            init_form();
        },1500);
    });
    var select_from_bank;
    function init_form(){
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