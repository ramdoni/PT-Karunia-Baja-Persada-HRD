@section('title', $data->debit_note)
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
                                    <select class="form-control" wire:model="bank_account_id" disabled>
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
                                    <input type="date" class="form-control" wire:model="date" disabled >
                                    @error('date')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <h5>{{$data->debit_note}}</h5>
                                <strong>No Polis : </strong>{{isset($data->policys->no_polis)?$data->policys->no_polis :''}}<br />
                                {{isset($data->policys->pemegang_polis) ? $data->policys->pemegang_polis : '-'}}<br />
                                <strong>Status</strong> : {!!status_income($data->status)!!}
                            </p>  
                        </div>
                    </div>
                    <div class="form-group table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    {{-- <th>No Voucher</th> --}}
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
                                <tr>
                                    <td class="text-right" colspan="2">
                                        Amount</td>
                                    <th>{{format_idr($data->nominal)}}</th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">Total</td>
                                    <th><h6>{{format_idr($total_debit)}}</h6></th>
                                    <th><h6>{{format_idr($total_kredit)}}</h6></th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="2">Outstanding Balance</td>
                                    <th colspan="2"><h6>{{format_idr($total_kredit-$total_debit)}}</h6></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <a href="javascript:void(0)" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>