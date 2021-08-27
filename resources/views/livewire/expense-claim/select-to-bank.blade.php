<div class="form-group">
    <label>{{ __('To Bank Account') }}</label>
    <select class="form-control select_to_bank" id="to_bank_account_id" wire:model="to_bank_account_id" {{$is_readonly?'disabled':''}}>
        <option value=""> --- None --- </option>
        @foreach (\App\Models\BankAccount::where('is_client',1)->orderBy('owner','ASC')->get() as $bank)
            <option value="{{ $bank->id }}">{{ $bank->bank}} - {{ $bank->no_rekening}} - {{ $bank->owner }}</option>
        @endforeach
    </select>
    @if(!$is_readonly)
        <a href="#" data-toggle="modal" data-target="#modal_add_bank"><i class="fa fa-plus"></i> Add Bank</a>
    @endif
</div>
