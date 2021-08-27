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
                                <input type="text" class="form-control" wire:model="recipient" {{$is_readonly?'disabled':''}} />
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference Type') }}</label>
                                <input type="text" class="form-control" wire:model="reference_type" {{$is_readonly?'disabled':''}} />
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference No') }}</label>
                                <input type="text" class="form-control" wire:model="reference_no" {{$is_readonly?'disabled':''}} />
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Bank Account') }}</label>
                                    <select class="form-control" wire:model="bank_account_id" {{$is_readonly?'disabled':''}}>
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
                                    <input type="date" class="form-control" wire:model="reference_date" {{$is_readonly?'disabled':''}} />
                                </div>
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <textarea class="form-control" wire:model="description" placeholder="Description" {{$is_readonly?'disabled':''}}></textarea>
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
                                        <th>Transaction Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($count_account as $k => $form)
                                    <tr>
                                        <td>
                                            <select class="form-control" wire:model="coa_id.{{$k}}" {{$is_readonly?'disabled':''}} wire:change="setNoVoucher({{$k}})">
                                                <option value=""> --- Account -- </option>
                                                @foreach(\App\Models\Coa::orderBy('name','ASC')->get() as $i)
                                                <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                                                @endforeach
                                            </select>
                                            @error("coa_id.".$k)
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
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
                                           <input type="date" class="form-control" wire:model="payment_date.{{$k}}" {{$is_readonly?'disabled':''}} />
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    @if(!$is_readonly)
                    <button type="button" wire:click="saveAsDraft" class="ml-3 btn btn-primary"><i class="fa fa-archive"></i> {{ __('Save as Draft') }}</button>
                    <button type="submit" class="ml-3 btn btn-warning"><i class="fa fa-save"></i> {{ __('Submit to Journal') }}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
@endpush
@section('page-script')
    Livewire.on('listenAddAccountForm', () =>{
        setTimeout(function(){
            $('.format_number').priceFormat({
                prefix: '',
                centsSeparator: '.',
                thousandsSeparator: '.',
                centsLimit: 0
            });
        },1000);
    });
    $('.format_number').priceFormat({
        prefix: '',
        centsSeparator: '.',
        thousandsSeparator: '.',
        centsLimit: 0
    });
@endsection