<div class="modal-content">
    <form wire:submit.prevent="cancel">
        <div class="modal-header text-danger">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-warning"></i> {{ __('Confirmation') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p>{{ __('Premi tidak tertagih ?') }}</p>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> {{ __('Submit') }}</button>
        </div>
    </form>
</div> 