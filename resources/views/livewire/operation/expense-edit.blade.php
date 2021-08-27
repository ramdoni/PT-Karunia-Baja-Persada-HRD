@section('title', 'Expense')
@section('parentPageTitle', 'Operation')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="row">
                        <div class="pr-5 col-md-6">
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
                                @error('recipient')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference Type') }}</label>
                                <select class="form-control" wire:model="reference_type" {{$is_readonly?'disabled':''}}>
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
                                <input type="text" class="form-control" wire:model="reference_no" {{$is_readonly?'disabled':''}} />
                                @error('reference_no')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Reference Date') }}</label>
                                <input type="date" class="form-control" wire:model="reference_date" {{$is_readonly?'disabled':''}} />
                                @error('reference_date')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <textarea class="form-control" wire:model="description" placeholder="Description" {{$is_readonly?'disabled':''}}></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('Amount (Rp)') }}</label>
                                <input type="text" class="form-control format_number" wire:model="nominal" {{$is_readonly?'disabled':''}} wire:input="calculate" />
                                @error('nominal')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="form-group col-md-7">
                                    <label>{{ __('Tax') }}</label>
                                    <select class="form-control" wire:model="tax_id" wire:change="calculate" {{$is_readonly?'disabled':''}}>
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\SalesTax::get() as $item)
                                        <option value="{{$item->id}}">({{$item->code}}) {{$item->description}} / {{$item->percen}}%</option> 
                                        @endforeach
                                    </select>
                                    @error('tax_id')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5">
                                    <label>{{ __('Tax Amount (Rp)') }}</label>
                                    <input type="text" class="form-control" readonly wire:model="tax_amount" {{$is_readonly?'disabled':''}} />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Total Amount (Rp)') }}</label>
                                <input type="text" class="form-control" readonly wire:model="total_amount" {{$is_readonly?'disabled':''}} />
                            </div>
                            <div class="form-group">
                                <label>{{ __('Payment Amount (Rp)') }}</label>
                                <input type="text" class="form-control format_number" wire:model="payment_amount" wire:input="calculate" {{$is_readonly?'disabled':''}} />
                                @error('payment_amount')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Outstanding Balance (Rp)') }}</label>
                                <input type="text" class="form-control" readonly wire:model="outstanding_balance" {{$is_readonly?'disabled':''}} />
                            </div>
                        </div>
                    </div>
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>