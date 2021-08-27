@section('title', 'Claim Payable')
@section('parentPageTitle', 'Expense')
<div class="clearfix row">
    <div class="col-md-5">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save('Submit')">
                    <div class="form-group">
                        <span>{{ __('Voucher Number') }} : <strong class="text-success">{{$no_voucher}}</strong></span>
                        @if(!$is_readonly)
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
                        @endif
                        <hr />
                    </div>
                    <div class="form-group">
                        <label>{{ __('No Polis') }}</label>
                        <select class="form-control select_no_polis" wire:model="no_polis" id="no_polis" {{$is_readonly?'disabled' : ''}}>
                            <option value=""> --- Select --- </option>
                            @foreach(\App\Models\Policy::orderBy('pemegang_polis','ASC')->get() as $item)
                            <option value="{{$item->id}}">{{$item->no_polis}} / {{$item->pemegang_polis}}</option>
                            @endforeach
                        </select>
                        @error('no_polis')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{__('Peserta')}}</label>
                        @foreach($add_pesertas as $k => $v)
                            <div class="form-group">
                                <input type="text" class="form-control mb-2" wire:model="no_peserta.{{$k}}" {{$is_readonly?'disabled':''}} placeholder="No Peserta" />
                                <input type="text" class="form-control" wire:model="nama_peserta.{{$k}}" {{$is_readonly?'disabled':''}} placeholder="Nama Peserta" />
                                @if(!$is_readonly)
                                <a href="javascript:;" class="text-danger" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash"></i> Delete</a>
                                @endif
                            </div>
                        @endforeach
                        @foreach($add_pesertas_temp as $k => $v)
                            <div class="form-group">
                                <input type="text" class="form-control mb-2" wire:model="no_peserta_temp.{{$k}}" placeholder="No Peserta" />
                                <input type="text" class="form-control" wire:model="nama_peserta_temp.{{$k}}" placeholder="Nama Peserta" />
                                <a href="javascript:;" class="text-danger" wire:click="delete_peserta_temp({{$k}})"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        @endforeach
                        @if(!$is_readonly)
                        <a href="javascript:;" wire:click="add_peserta"><i class="fa fa-plus"></i> Add Peserta</a>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{ __('Reference No') }}</label>
                        <input type="text" class="form-control" wire:model="reference_no" {{$is_readonly?'disabled':''}} />
                        @error('reference_no')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('From Bank Account') }}</label>
                        <select class="form-control" wire:model="from_bank_account_id" {{$is_readonly?'disabled':''}}>
                            <option value=""> --- Select --- </option>
                            @foreach (\App\Models\BankAccount::where('is_client',0)->orderBy('owner','ASC')->get() as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank}} - {{ $bank->no_rekening}} - {{ $bank->owner }}</option>
                            @endforeach
                        </select>
                        @error('from_bank_account_id')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    @livewire('expense-claim.select-to-bank', ['is_readonly'=> $is_readonly,'to_bank_account_id'=> $expense->rekening_bank_id ? $expense->rekening_bank_id : 0])
                    <div class="form-group">
                        @error('to_bank_account_id')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Payment Amount') }}</label>
                            <input type="text" class="form-control format_number text-right" {{$is_readonly?'disabled':''}} wire:model="nilai_klaim">
                            @error('nilai_klaim')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Bank Charges') }}</label>
                            <input type="text" class="form-control format_number  text-right" {{$is_readonly?'disabled':''}} wire:model="bank_charges">
                            @error('bank_charges')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Payment Date') }}</label>
                        <input type="date" class="form-control col-md-6" {{$is_readonly?'disabled':''}} wire:model="payment_date">
                        @error('payment_date')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <textarea style="height:100px;" placeholder="{{__('Description')}}" {{$is_readonly?'disabled':''}} class="form-control" wire:model="description"></textarea>
                    </div>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    @if(!$is_readonly)
                    <button type="submit" class="ml-3 btn btn-primary" {{!$is_submit?'disabled':''}}><span title="{{!$is_submit? "Claim tidak bisa dilanjutkan karna belum ada premi yang masuk" : ''}}"><i class="fa fa-save"></i> {{ __('Submit') }}</span></button>
                    <button type="button" class="ml-3 btn btn-info float-right" wire:click="save('Draft')"><i class="fa fa-save"></i> {{ __('Save as Draft') }}</button>
                    @endif
                    <div wire:loading wire:target="save">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
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
                    @if(isset($data) and $data->type==1)
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
                    @endif
                    @if(isset($data) and $data->type==2)
                    <tr>
                        <th>Peserta</th>
                        <td>:</td>
                        <td>{{isset($data->reas_syariah->peserta) ? $data->reas_syariah->peserta : ''}}</td>
                    </tr>
                    <tr>
                        <th>Keterangan T/F</th>
                        <td>:</td>
                        <td>{{isset($data->reas_syariah->keterangan) ? $data->reas_syariah->keterangan : ''}}</td>
                    </tr>
                    <tr>
                        <th>Broker Re / Reasuradur</th>
                        <td>:</td>
                        <td>{{isset($data->reas_syariah->broker_re_reasuradur) ? $data->reas_syariah->broker_re_reasuradur : ''}}</td>
                    </tr>
                    <tr>
                        <th>Premi Reas</th>
                        <td>:</td>
                        <td>{{isset($data->reas_syariah->kontribusi_reas_netto) ? format_idr($data->reas_syariah->kontribusi_reas_netto) : ''}}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Premium Receivable</h6>
                        <hr />
                        <div class="table-responsive" style="max-height: 400px;">
                            <table class="table table-striped table-hover m-b-0 c_list">
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
<livewire:expense-claim.add-bank />
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
@endpush
@section('page-script')
    Livewire.on('emit-add-bank',()=>{
        $("#modal_add_bank").modal("hide");
    });
    Livewire.on('init-form', () =>{
        setTimeout(function(){
            init_form();
        },500);
    });
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

    }

    function init_bank(){
        select_to_bank = $('.select_to_bank').select2();
        $('.select_to_bank').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected_to_bank = $('.select_to_bank').find(':selected').val();
        if(selected_to_bank !="") select_to_bank.val(selected_to_bank);   
    }
    
    Livewire.on('init-bank', () =>{
        setTimeout(function(){
            init_bank();  
        },500);
    });

    setTimeout(function(){
        init_form();
        init_bank();
    });
@endsection