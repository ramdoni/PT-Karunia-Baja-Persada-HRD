@section('title', __('Account Receivable'))
@section('parentPageTitle', 'Home')
<div class="clearfix row">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('Account') }}</label>
                        <select class="form-control" wire:model="coa_id" wire:change="setNoVoucher">
                            <option value=""> --- Select --- </option>
                            @foreach(\App\Models\Coa::orderBy('name','ASC')->get() as $k =>$i)
                            <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                            @endforeach
                        </select>
                        @error('coa_group_id')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('No Voucher') }}</label>
                        <input type="text" class="form-control" readonly wire:model="no_voucher" >
                        @error('code')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>{{ __('Date') }}</label>
                            <input type="date" class="form-control"  wire:model="date_journal" >
                            @error('date_journal')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>{{ __('Nominal') }}</label>
                            <input type="text" class="form-control"  wire:model="nominal" >
                            @error('nominal')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea class="form-control" wire:model="description" style="height:100px;"></textarea>
                        @error('description')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <hr>
                    <a href="{{route('account-receivable')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>