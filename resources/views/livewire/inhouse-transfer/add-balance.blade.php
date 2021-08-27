<div class="modal-content">
    <form wire:submit.prevent="save">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Open Balance</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Bank Account</label>
                <select class="form-control" wire:model="bank_account_id">
                    <option value=""> --- Select --- </option>
                    @foreach(\App\Models\BankAccount::where('is_client',0)->get() as $bank)
                    <option value="{{ $bank->id}}">{{ $bank->bank}} - {{ $bank->no_rekening}} - {{ $bank->owner }}</option>
                    @endforeach
                </select>
                @error('bank_account_id')
                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                @enderror
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Open Balance</label>
                    <input type="text" class="form-control format_number" wire:model="nominal" />
                    @error('nominal')
                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label>Transaction Date</label>
                    <input type="date" class="form-control" wire:model="date" />
                    @error('date')
                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Submit</button>
        </div>
    </form>
</div>