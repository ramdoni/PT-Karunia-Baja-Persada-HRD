<div class="modal-content">
    <form wire:submit.prevent="save">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Extend Due Date <span class="text-danger">{{date('d M Y',strtotime($data->due_date))}}</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true close-btn">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-3">
                    <label>Days</label>
                    <input type="number" class="form-control" wire:model="days" /> 
                </div>
                <div class="form-group col-md-6">
                    <label>Due Date</label>
                    <input type="date" class="form-control" wire:model="date" min="{{$data->due_date}}" /> 
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