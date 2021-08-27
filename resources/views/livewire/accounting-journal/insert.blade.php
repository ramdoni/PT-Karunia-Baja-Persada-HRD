<form wire:submit.prevent="save">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Journal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true close-btn">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Journal Date </label>
                <input type="date" class="form-control" wire:model="date_journal" />
            </div>
        </div>
        <table class="table">
            @foreach($array_coa as $k=>$v)
            <tr>
                <td class="px-0 text-grey"><a href="javascript:;" class="text-danger" wire:click="delete({{$k}})"><i class="fa fa-times"></i></a></td>
                <td style="width: 40%"> 
                    <select class="form-control select_coa" id="coa_id.{{$k}}" wire:model="coa_id.{{$k}}">
                        <option value=""> --- Account -- </option>
                        @foreach(\App\Models\CoaGroup::orderBy('name','ASC')->get() as $group)
                            <optgroup label="{{$group->name}}">
                                @foreach(\App\Models\Coa::where('coa_group_id',$group->id)->orderBy('name','ASC')->get() as $i)
                                <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error("coa_id.{{$k}}")
                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                    @enderror
                </td>
                <td class="px-0"><input type="text" class="form-control" wire:model="description.{{$k}}" placeholder="Description" /></td>
                <td><input type="text" class="form-control text-right format_number" wire:model="debit.{{$k}}" placeholder="Debit" /></td>
                <td><input type="text" class="form-control text-right format_number" wire:model="kredit.{{$k}}" placeholder="Kredit" /></td>
            </tr>
            @endforeach
        </table>
        <a href="javascript:;" wire:click="add_account"><i class="fa fa-plus"></i> Add Account</a>
    </div>
    <div class="modal-footer">
        <div wire:loading>
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
        <button type="submit" {{!$is_submit?'disabled' : ''}} class="btn btn-info close-modal"><i class="fa fa-save"></i> Submit Journal</button>
    </div>
</form>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;padding-left:10px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
<script>
    Livewire.on('init-form', ()=>{
        init_form();
    });
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        $('.select_coa').each(function(){
            var select__2 = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected__ = $(this).find(':selected').val();
            if(selected__ !="") select__2.val(selected__);
        });
    }
    setTimeout(function(){
        init_form()
    });
</script>
@endpush