@section('title', 'Reinsurance Commision '.$data->no_voucher)
@section('parentPageTitle', 'Income')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table pl-0 mb-0 table-striped">
                                <tr>
                                    <th>{{ __('Voucher Number')}}</th>
                                    <td>{!!no_voucher($data)!!}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Voucher Date')}}</th>
                                    <td>{{date('d M Y',strtotime($data->created_at))}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Policy Number / Policy Holder')}}</th>
                                    <td>{{$data->client}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Debit Note / Kwitansi Number')}}</th>
                                    <td>{{$data->reference_no}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Reference Date')}}</th>
                                    <td>{{$data->reference_date}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Amount')}}</th>
                                    <td>{{format_idr($data->nominal)}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Payment Amount')}}</th>
                                    <td><input type="text" class="form-control format_number" {{$is_readonly?'disabled':''}} wire:model="payment_amount" /></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Outstanding Balance')}}</th>
                                    <td>{{format_idr($this->outstanding_balance)}}</td>
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
                                        @if($total_titipan_premi <= $data->nominal and !$is_readonly)
                                        <a href="javascript:void(0)" data-target="#modal_add_titipan_premi" data-toggle="modal"><i class="fa fa-plus"></i> Premium Deposit</a>
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
                                        @enderror<br />
                                        @if(!$is_readonly)
                                        <a href="#" data-toggle="modal" data-target="#modal_add_bank"><i class="fa fa-plus"></i> Add Bank</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{__('To Bank Account')}}</th>
                                    <td>
                                        <select class="form-control" wire:model="bank_account_id" {{$is_readonly?'disabled':''}}>
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
                                    <th>{{__('Bank Charges')}}</th>
                                    <td><input type="text" {{$is_readonly?'disabled':''}} class="form-control format_number col-md-6" wire:model="bank_charges" /></td>
                                </tr>
                                <tr>
                                    <th>{{__('Description')}}</th>
                                    <td>
                                        <textarea style="height:100px;" {{$is_readonly?'disabled':''}} class="form-control" wire:model="description"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    @if(!$is_readonly)
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                    @endif
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5 px-0">
        <div class="card mt-0">
            <div wire:loading style="position:absolute;right:0;">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
            <div class="body" style="max-height:700px;overflow-y:scroll">
                <h6 class="text-success">{{$data->reference_no}}</h6>
                <hr />
                <table class="table pl-0 mb-0 table-striped table-nowrap"> 
                    @foreach(\Illuminate\Support\Facades\Schema::getColumnListing('konven_underwriting') as $column)
                    @if($column=='id' || $column=='created_at'||$column=='updated_at') @continue @endif
                    <tr>
                        <th style="width:40%;">{{ ucfirst($column) }}</th>
                        <td style="width:60%;">{{ in_array($column,['up_peserta_pending','premi_peserta_pending','up','premi_gross','extra_premi','jumlah_discount','handling_fee','jumlah_fee','jumlah_pph','jumlah_ppn','biaya_polis','biaya_sertifikat','extsertifikat','premi_netto','total_gross_kwitansi']) ? format_idr($data->uw->$column) : $data->uw->$column }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <livewire:general.add-titipan-premi />
</div>
<div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:income-reinsurance.add-bank />
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
@endpush
@section('page-script')
    Livewire.on('init-form', () =>{
        init_form();
    });
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        select__2 = $('.from_bank_account').select2();
        $('.from_bank_account').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('.from_bank_account').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
    }
    setTimeout(function(){
        init_form()
    })
@endsection