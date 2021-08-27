@section('title', $data->reference_no)
@section('parentPageTitle', 'Account Receivable')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="px-0 form-group col-md-5">
                                <label>{{ __('No Voucher') }}<small>(Generate Automatic)</small></label>
                                <input type="text" class="form-control" wire:model="no_voucher" readonly >
                                @error('no_voucher')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>{{ __('Rekening Bank') }}</label>
                                    <select class="form-control" wire:model="bank_account_id" {{$is_readonly?'disabled':''}}>
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\BankAccount::orderBy('bank','ASC')->get() as $i)
                                        <option value="{{ $i->id}}">{{$i->bank}} - {{$i->no_rekening}} - {{$i->owner}}</option>
                                        @endforeach
                                    </select>
                                    @error('rekening_bank_id')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <div class="px-0 form-group col-md-2">
                                    <label>{{ __('Date') }}</label>
                                    <input type="date" class="form-control" wire:model="date" {{$is_readonly?'disabled':''}}>
                                    @error('date')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <h5>{{$data->reference_no}}</h5>
                                <strong>Recipient : </strong>{{isset($data->recipient)?$data->recipient :''}}<br />
                                <strong>Reference Type : </strong>{{$data->reference_type}}<br />
                                <strong>Status</strong> : {!!status_income($data->status)!!}
                            </p>  
                        </div>
                    </div>
                    <div class="form-group table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th style="width:10px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->journals as $i)
                                <tr>
                                    <td>{{isset($i->coa->name)?$i->coa->name:''}}</td>
                                    <td>{{$i->description}}</td>
                                    <td>{{format_idr($i->debit)}}</td>
                                    <td>{{format_idr($i->kredit)}}</td>
                                </tr>
                                @endforeach
                                @if($data->status!=3)
                                    @foreach($count_account as $k => $form)
                                    <tr>
                                        <td>
                                            <select class="form-control" wire:model="coa_id.{{$k}}" wire:change="setNoVoucher({{$k}})">
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
                                            <input type="text" class="form-control" wire:model="description.{{$k}}" />
                                        </td>
                                        <td style="width:10%;">
                                            <input type="text" class="form-control format_number" wire:model="debit.{{$k}}" wire:input="sumDebit" />
                                            @error("debit.{{$k}}")
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                        </td>
                                        <td style="width:10%;"> 
                                            <input type="text" class="form-control format_number" wire:model="kredit.{{$k}}" wire:input="sumKredit" />
                                            @error("kredit.{{$k}}")
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                            @enderror
                                        </td>
                                        <td>
                                            @if($k>0)
                                            <a href="javascript:void(0)" wire:click="deleteAccountForm({{$k}})" class="text-danger"><i class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="text-right" colspan="2">
                                        <a href="javascript:void(0)" class="float-left btn btn-info btn-sm" wire:click="addAccountForm"><i class="fa fa-plus"></i> Account</a>
                                        Total</td>
                                    <th><h6>{{format_idr($total_debit)}}</h6></th>
                                    <th><h6>{{format_idr($total_kredit)}}</h6></th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">Amount</td>
                                    <th colspan="2">{{format_idr($data->nominal)}}</th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">Outstanding Balance</td>
                                    <th colspan="2"><h6>{{format_idr($data->nominal-$total_debit)}}</h6></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    @if($data->status!=3)
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
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
    $('.format_number').priceFormat({
        prefix: '',
        centsSeparator: '.',
        thousandsSeparator: '.',
        centsLimit: 0
    });
@endsection