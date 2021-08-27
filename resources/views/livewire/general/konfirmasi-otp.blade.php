<div>
    <div wire:ignore.self class="modal fade" id="modal_konfirmasi_otp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="submit">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Request OTP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Perubahan data ini membutuhkan otorisasi dari Supervisor, silahkan request OTP dan masukan OTP untuk melakukan perubahan ini.</p>
                        <span class="text-success">{{$message}}</span>
                        <span class="text-danger">{{$danger}}</span>
                        <div class="row">
                            <div class="form-group col-md-4 mb-0">
                                <input type="number" class="form-control" wire:model="otp" placeholder="OTP" /> 
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <a href="javascript:;" class="btn btn-info btn-sm" wire:click="request_otp">Request OTP ke Supervisor</a>
                            </div>          
                            <div class="col-md-12 form-group">
                            @error('otp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script>
    Livewire.on('otp-editable',()=>{
        $("#modal_konfirmasi_otp").modal("hide");
    });
</script>
@endpush