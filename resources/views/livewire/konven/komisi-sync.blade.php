<form wire:submit.prevent="komisi_sync">
    <div class="modal-header">
        @if(!$is_sync_komisi)
        <div wire:loading.remove>
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-refresh"></i> Sync Data</h5>
        </div>
        @endif
        @if($is_sync_komisi)
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>
                <span class="sr-only">Loading...</span>  Sync Data {{$total_finish}} / {{$total_sync}}</h5>
        @endif
    </div>
    <div class="modal-body">
        <div class="form-group" wire:loading.remove>
            <label>Synchronize Komisi Data ?</label>
        </div>
        <div wire:loading class="form-group">
            <p>{!!$data!!}</p>
        </div>
    </div>
    <div class="modal-footer">
        <a href="javascript:void(0)" wire:click="cancel_sync" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-sm">Cancel</a>
        <button type="submit" {{$is_sync_komisi?'disabled':''}} wire:click="$set('is_sync_komisi',true)" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i> Sync</button>
    </div>
</form>
