<form wire:submit.prevent="save">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Set Code Cashflow</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true close-btn">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>Code Cashflow</label>
            <select class="form-control" wire:model="code_cashflow_id">
                <option value=""> --- Select --- </option>
                @foreach(get_group_cashflow() as $k=>$i)
                <optgroup label="{{$i}}">
                    @foreach(\App\Models\CodeCashflow::where('group',$k)->get() as $k => $item)
                        <option value="{{$item->id}}">{{$item->name}} - {{$item->code}}</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
            @error('code_cashflow_id')
            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
            @enderror
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info close-modal" wire:click="$emit('listenUploaded');"><i class="fa fa-save"></i> Update</button>
    </div>
    <div wire:loading>
        <div class="page-loader-wrapper" style="display:block">
            <div class="loader" style="display:block">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                <p>Please wait...</p>        
            </div>
        </div>
    </div>
</form>

