<form>
    <div class="modal-header">
        @if(!$is_sync)
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-refresh"></i> Sync Data </h5>
        @endif
        @if($is_sync)
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>
                <span class="sr-only">Loading...</span>  Sync Data {{$total_finish}} / {{$total_sync}}</h5>
        @endif
    </div>
    <div class="modal-body">
        <div class="form-group" wire:loading.remove>
            <label>Synchronize Underwriting Data?</label>
        </div>
        <div wire:loading class="form-group">
            <p>{!!$data!!}</p>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" wire:click="cancel_sync" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-sm">Cancel</a>
        <button type="button" {{$is_sync?'disabled':''}} wire:click="start_sync" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Sync</button>
    </div>
</form>
