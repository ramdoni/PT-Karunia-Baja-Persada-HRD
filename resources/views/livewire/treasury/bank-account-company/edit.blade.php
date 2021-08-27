@section('title', __('Bank Account'))
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('Code') }}</label>
                        <input type="text" class="form-control" wire:model="code" >
                        @error('code')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Bank') }}</label>
                        <input type="text" class="form-control" wire:model="bank" >
                        @error('bank')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('No Rekening') }}</label>
                        <input type="text" class="form-control" wire:model="no_rekening" >
                        @error('no_rekening')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Owner') }}</label>
                        <input type="text" class="form-control" wire:model="owner" >
                        @error('owner')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Opening Balance') }}</label>
                        <input type="text" class="form-control" wire:model="open_balance" >
                        @error('open_balance')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Cabang') }}</label>
                        <textarea class="form-control" wire:model="cabang" style="height:100px;"></textarea>
                        @error('cabang')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Chart of Account (COA)') }}</label>
                        <select class="form-control select2" id="coa_id" wire:model="coa_id">
                            <option value=""> --- Account -- </option>
                            @foreach(\App\Models\CoaGroup::orderBy('name','ASC')->get() as $group)
                                <optgroup label="{{$group->name}}">
                                    @foreach(\App\Models\Coa::where('coa_group_id',$group->id)->orderBy('name','ASC')->get() as $i)
                                    <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('cabang')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <hr>
                    <a href="{{route('bank-account')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>