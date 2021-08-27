@section('title', 'Account Payable')
@section('parentPageTitle', 'Operation')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="saveToJournal">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('No Voucher') }}<small>(Generate Automatic)</small></label>
                                <input type="text" class="form-control" wire:model="no_voucher" readonly >
                                @error('no_voucher')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Recipient') }}</label>
                                <input type="text" class="form-control" wire:model="recipient" />
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
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference No') }}</label>
                                <input type="text" class="form-control" wire:model="reference_no" />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Bank Account') }}</label>
                                    <select class="form-control" wire:model="bank_account_id">
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\BankAccount::orderBy('bank','ASC')->get() as $i)
                                        <option value="{{ $i->id}}">{{$i->bank}} - {{$i->no_rekening}} - {{$i->owner}}</option>
                                        @endforeach
                                    </select>
                                    @error('bank_account_id')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="pl-0 form-group col-md-6">
                                    <label>{{ __('Reference Date') }}</label>
                                    <input type="date" class="form-control" wire:model="reference_date" />
                                </div>
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <textarea class="form-control" wire:model="description" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group table-responsive col-md-9">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Transaction <br/>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($count_account as $k => $form)
                                    <tr>
                                        <td style="width: 30%">
                                            <select class="form-control select2" id="coa_id.{{$k}}" wire:model="coa_id.{{$k}}" wire:change="setNoVoucher({{$k}})">
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
                                        </td>
                                        <td>
                                            <textarea class="form-control" wire:model="description_coa.{{$k}}" style="height:37px;"></textarea>
                                        </td>
                                        <td style="width:15%;">
                                            <input type="text" class="form-control format_number" wire:model="debit.{{$k}}" wire:input="sumDebit" />
                                            @error("debit.{{$k}}")
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                        </td>
                                        <td style="width:15%;"> 
                                            <input type="text" class="form-control format_number" wire:model="kredit.{{$k}}" wire:input="sumKredit" />
                                            @error("kredit.{{$k}}")
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                        </td>
                                        <td>
                                           <input type="date" class="form-control" wire:model="payment_date.{{$k}}" />
                                        </td>
                                        <td>@if($k!=0)<a href="javascript:void(0)" wire:click="deleteAccountForm({{$k}})" class="text-danger"><i class="fa fa-trash"></i></a>@endif</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="2">
                                            <a href="javascript:void(0)" class="float-left btn btn-info btn-sm" wire:click="addAccountForm"><i class="fa fa-plus"></i> Account</a>
                                            Total</td>
                                        <th><h6>{{format_idr($total_debit)}}</h6></th>
                                        <th><h6>{{format_idr($total_kredit)}}</h6></th>
                                        <th></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="button" wire:click="saveAsDraft" class="ml-3 btn btn-primary"><i class="fa fa-archive"></i> {{ __('Save as Draft') }}</button>
                    <button type="submit" class="ml-3 btn btn-warning"><i class="fa fa-save"></i> {{ __('Submit to Journal') }}</button>
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
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        {{-- $('.select2').select2();
        $('.select2').each(function(){
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
        }); --}}
    }
    setTimeout(function(){
        init_form()
    })
    Livewire.on('listenAddAccountForm', () =>{
        setTimeout(function(){
            init_form();
        },500);
    });
@endsection