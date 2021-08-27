@section('title', 'Premium Deposit')
@section('parentPageTitle', 'Income')
<div class="clearfix row">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table pl-0 mb-0 table-striped">
                                <tr>
                                    <th>{{ __('Voucher Number')}}</th>
                                    <td>{{$no_voucher}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Voucher Date')}}</th>
                                    <td>{{date('d F Y')}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Reference No')}}</th>
                                    <td>{{$data->reference_no}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Payment Date')}}</th>
                                    <td>{{$data->payment_date}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('From Bank Account')}}</th>
                                    <td>{{isset($data->from_bank_account->no_rekening) ? $data->from_bank_account->no_rekening .'- '.$data->from_bank_account->bank.' an '. $data->from_bank_account->owner : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('To Bank Account')}}</th>
                                    <td>{{isset($data->bank_account->no_rekening) ? $data->bank_account->no_rekening .' - '.$data->bank_account->bank.' an '. $data->bank_account->owner : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Nominal')}}</th>
                                    <td>{{format_idr($data->nominal)}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Premium Receivable')}}</th>
                                    <td class="text-danger">{{format_idr($data->payment_amount)}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Balance')}}</th>
                                    <td class="text-info">{{format_idr($data->outstanding_balance)}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Description')}}</th>
                                    <td>{{$data->description}}</td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 px-0">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Transaction</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <h5 class="text-danger">{{format_idr($data->titipan_premi->sum('nominal'))}}</h5>
                    </div>
                </div>
                <hr />
                @foreach($data->titipan_premi as $item)
                    @if($item->premi)
                    <h6>{{$item->transaction_type}}</h6>
                    <p><label>No Voucher </label> : {{$item->premi->no_voucher}}<br />
                        {{$item->premi->reference_no}} / {{$item->premi->client}} <br /><strong class="text-danger">Rp. {{format_idr($item->nominal)}}</strong>
                    </p>
                    <hr />
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <livewire:income-premium-receivable.add-bank />
        </div>
    </div>
</div>
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
@endpush
@section('page-script')
document.addEventListener("livewire:load", () => {
    init_form();
});
$(document).ready(function() {
    setTimeout(function(){
        init_form()
    })
});
var select__2;
function init_form(){
    $('.format_number').priceFormat({
        prefix: '',
        centsSeparator: '.',
        thousandsSeparator: '.',
        centsLimit: 0
    });
    select__2 = $('.from_bank_account').select2();
    $('.from_bank_account').on('change', function (e) {
        let elementName = $(this).attr('id');
        var data = $(this).select2("val");
        @this.set(elementName, data);
    });
    var selected__ = $('.from_bank_account').find(':selected').val();
    if(selected__ !="") select__2.val(selected__);
}
Livewire.on('init-form',()=>{
    init_form();
});
Livewire.on('emit-add-bank',id=>{
    $("#modal_add_bank").modal('hide');
    select__2.val(id);
})
@endsection