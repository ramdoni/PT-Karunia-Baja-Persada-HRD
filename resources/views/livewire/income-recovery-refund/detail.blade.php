@section('title', 'Recovery Refund')
@section('parentPageTitle', 'Income')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form wire:submit.prevent="save">
                    <table class="table pl-0 mb-0 table-striped table-nowrap">
                        <tr>
                            <th>{{ __('Voucher Number') }}</th>
                            <td>{!!no_voucher($income)!!}</td>
                        </tr>
                        <tr>
                            <th style="width:35%">{{ __('No Polis') }}</th>
                            <td style="width: 65%;">{{isset($income->policys->no_polis) ? $income->policys->no_polis .' / '. $income->policys->pemegang_polis : ''}}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Reference Date') }}</th>
                            <td>{{date('d-M-Y',strtotime($income->reference_date))}}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Reference No') }}</th>
                            <td>{{$income->reference_no}}</td>
                        </tr>
                        <tr>
                            <th>Peserta</th>
                            <td>
                                @foreach($add_pesertas as $k => $v)
                                <p>{{$v->no_peserta}} / {{$v->nama_peserta}}</p>
                                <hr />
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Total Payment Amount')}}</th>
                            <td>{{format_idr($income->payment_amount)}}</td>
                        </tr>
                        <tr>
                            <th>Premium Deposit</th>
                            <td>
                                @if($titipan_premi)
                                    @foreach($titipan_premi as $item)
                                    @php($titipan = $item->titipan)
                                    <p>
                                        No Voucher : <a href="{{route('income.titipan-premi.detail',$titipan->id)}}" target="_blank">{{$titipan->no_voucher}}</a> <br />
                                        {{isset($titipan->from_bank_account->no_rekening) ? $titipan->from_bank_account->no_rekening .'- '.$titipan->from_bank_account->bank.' an '. $titipan->from_bank_account->owner : '-'}} <br />
                                         <strong>{{format_idr($item->nominal)}}</strong>
                                    </p>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('Bank Charges')}}</th>
                            <td>{{format_idr($income->bank_charges)}}</td>
                        </tr>
                        <tr>
                            <th>{{__('From Bank Account')}}</th>
                            <td>
                                @if(isset($income->from_bank_account->owner))
                                {{ $income->from_bank_account->owner }} - {{ $income->from_bank_account->no_rekening}} {{ $income->from_bank_account->bank}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('To Bank Account')}}</th>
                            <td>
                                @if(isset($income->bank_account->owner))
                                {{ $income->bank_account->bank}} - {{ $income->bank_account->no_rekening}}  - {{ $income->bank_account->owner }} 
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('Payment Date')}}*<small>{{__('Default today')}}</small></th>
                            <td>{{date('d-M-Y',strtotime($income->payment_date))}}</td>
                        </tr>
                        
                        <tr>
                            <th>{{__('Description')}}</th>
                            <td>{{$income->description}}</td>
                        </tr>
                    </table>
                    <hr />
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="body">
                <table class="table table-striped table-hover m-b-0 c_list table-nowrap">
                    <tr>
                        <th>No Polis</th>
                        <td> :</td>
                        <td>{{isset($data->no_polis) ? $data->no_polis : ''}} 
                            @if($data)
                                @if($data->type==1)
                                    <span class="badge badge-info">Konven</span>
                                @else
                                    <span class="badge badge-warning">Syariah</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Pemegang Polis</th>
                        <td>:</td>
                        <td>{{isset($data->pemegang_polis) ? $data->pemegang_polis : ''}}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>:</td>
                        <td>{{isset($data->alamat) ? $data->alamat : ''}}</td>
                    </tr>
                    <tr>
                        <th>Produk</th>
                        <td>:</td>
                        <td>{{isset($data->produk) ? $data->produk : ''}}</td>
                    </tr>
                    <tr>
                        <th>Peserta</th>
                        <td>:</td>
                        <td>{{isset($data->reas->peserta) ? $data->reas->peserta : ''}}</td>
                    </tr>
                    <tr>
                        <th>Peserta</th>
                        <td>:</td>
                        <td>{{isset($data->reas->peserta) ? $data->reas->peserta : ''}}</td>
                    </tr>
                    <tr>
                        <th>Keterangan T/F</th>
                        <td>:</td>
                        <td>{{isset($data->reas->keterangan) ? $data->reas->keterangan : ''}}</td>
                    </tr>
                    <tr>
                        <th>Broker Re / Reasuradur</th>
                        <td>:</td>
                        <td>{{isset($data->reas->broker_re) ? $data->reas->broker_re : ''}}</td>
                    </tr>
                    <tr>
                        <th>Premi Reas</th>
                        <td>:</td>
                        <td>{{isset($data->reas->premi_reas_netto) ? format_idr($data->reas->premi_reas_netto) : ''}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:income-recovery-refund.add-bank />
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
    Livewire.on('init-form', () =>{
        setTimeout(function(){
            init_form();
        },1500);
    });
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        
        select__2 = $('.select_no_polis').select2();
        $('.select_no_polis').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('.select_no_polis').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);

        select_from_bank = $('.from_bank_account').select2();
        $('.from_bank_account').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__from_bank = $('.from_bank_account').find(':selected').val();
        if(select_from_bank !="") select_from_bank.val(selected__from_bank);
        
        $('.select_to_bank').each(function(){
            select_to_bank = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected_to_bank = $(this).find(':selected').val();
            if(selected_to_bank !="") select_to_bank.val(selected_to_bank);
        });
    }
    setTimeout(function(){
        init_form()
    })
@endsection