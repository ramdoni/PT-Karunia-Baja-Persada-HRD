@section('title', __('Code Cashflow'))
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-md-4">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>{{ __('Group') }}</label>
                        <select class="form-control" wire:model="group" disabled>
                            <option value=""> --- Select --- </option>
                            @foreach(get_group_cashflow() as $k =>$i)
                            <option value="{{$k}}">{{$i}}</option>
                            @endforeach
                        </select>
                        @error('group')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Code') }}</label>
                        <input type="text" class="form-control" wire:model="code" disabled>
                        @error('code')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" class="form-control"  wire:model="name" >
                        @error('name')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <hr>
                    <a href="{{route('code-cashflow')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>