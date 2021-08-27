@section('title', 'Income - '. $data->no_voucher)
@section('parentPageTitle', 'Operation')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="saveToJournal">
                    <div class="row">
                        <div class="pr-5 col-md-4">
                            <table class="table">
                                <tr>
                                    <th>{{ __('Voucher Number')}}</th>
                                    <td>{{$data->no_voucher}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Client')}}</th>
                                    <td>{{$data->client}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Reference Type')}}</th>
                                    <td>{{$data->reference_type}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Reference No')}}</th>
                                    <td>{{$data->reference_no}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Reference Date')}}</th>
                                    <td>{{$data->reference_date}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Description')}}</th>
                                    <td>{{$data->description}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table">
                                <tr>
                                    <th>{{ __('Amount (Rp)')}}</th>
                                    <td>{{format_idr($data->nominal)}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Tax')}}</th>
                                    <td>@if(isset($data->tax->code)) {{$data->tax->code}} {{$data->tax->description}} / {{$data->tax->percen}}@else - @endif</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Tax Amount')}}</th>
                                    <td><span class="btn btn-outline-danger btn-sm">{{format_idr($data->tax_amount)}}</span></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Total Amount')}}</th>
                                    <td><span class="btn btn-outline-danger btn-sm">{{format_idr($total_amount)}}</span></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Payment Amount')}}</th>
                                    <td><span class="btn btn-outline-danger btn-sm">{{format_idr($payment_amount)}}</span></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Outstanding Balance')}}</th>
                                    <td>{{format_idr($data->outstanding_balance)}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($count_account as $k => $form)
                                <tr>
                                    <td style="width:40%;">
                                        <div>
                                            <select class="form-control select2" id="coa_id.{{$k}}" wire:model="coa_id.{{$k}}" {{$is_readonly?'disabled':''}} wire:change="setNoVoucher({{$k}})">
                                                <option value=""> --- Account -- </option>
                                                @foreach(\App\Models\CoaGroup::orderBy('name','ASC')->get() as $group)
                                                    <optgroup label="{{$group->name}}">
                                                        @foreach(\App\Models\Coa::where('coa_group_id',$group->id)->orderBy('name','ASC')->get() as $i)
                                                        <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                            @error("coa_id.".$k)
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" {{$is_readonly?'disabled':''}} wire:model="description_coa.{{$k}}" />
                                    </td>
                                    <td style="width:10%;">
                                        <input type="text" class="form-control format_number" wire:model="debit.{{$k}}" {{$is_readonly?'disabled':''}} wire:input="sumDebit" />
                                        @error("debit.{{$k}}")
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td style="width:10%;"> 
                                        <input type="text" class="form-control format_number" wire:model="kredit.{{$k}}" {{$is_readonly?'disabled':''}} wire:input="sumKredit" />
                                        @error("kredit.{{$k}}")
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        @if(!$is_readonly)
                                            @if($k!=0)<a href="javascript:void(0)" wire:click="deleteAccountForm({{$k}})" class="text-danger"><i class="fa fa-trash"></i></a>@endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="2">
                                        @if(!$is_readonly)
                                        <a href="javascript:void(0)" class="float-left btn btn-info btn-sm" wire:click="addAccountForm"><i class="fa fa-plus"></i> Account</a>
                                        @endif
                                        Total</td>
                                    <th><h6>{{format_idr($total_debit)}}</h6></th>
                                    <th><h6>{{format_idr($total_kredit)}}</h6></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">
                                    Outstanding Balance
                                    </td>
                                    <th colspan="2">{{format_idr(abs($total_kredit - $total_debit))}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    @if(!$is_readonly)
                    <button type="button" wire:click="saveAsDraft" class="ml-3 btn btn-primary"><i class="fa fa-archive"></i> {{ __('Save as Draft') }}</button>
                    <button type="submit" {{!$is_submit_journal?'disabled':''}} class="ml-3 btn btn-warning"><i class="fa fa-save"></i> {{ __('Submit to Journal') }}</button>
                    @endif
                </form> 
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
@endpush
@section('page-script')

    document.addEventListener("livewire:load", () => {
        init_form();
    });
    
    Livewire.on('changeForm', () =>{
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
        $('.select2').each(function(){
            var select__2 = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected__ = $(this).find(':selected').val();
            if(selected__!="") select__2.val(selected__);
        });
    }
@endsection