@section('title', 'Konven')
@section('parentPageTitle', 'General Ledger')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-3" wire:ignore>
                        <select class="form-control coa_group" wire:model="coa_group_id">
                            <option value=""> -- All Group -- </option>
                            @foreach(\App\Models\CoaGroup::orderBy('name',"ASC")->get() as $group)
                            <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="col-md-2">
                        <input type="text" class="form-control journal_date" placeholder="Journal Date" />
                    </div> --}}
                    <div class="col-md-8">
                        <button type="button" wire:click="$emit('preview-gl')" data-toggle="modal" data-target="#submit_or_preview" class="btn btn-info"><i class="fa fa-save"></i> Submit / Preview General Ledger ({{$total_selected}})</button>
                        @if($total_selected>0)
                        <a href="javascript:;" class="btn btn-danger" wire:click="clear_selected">Clear Selected</a>
                        @endif
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <hr />
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-bordered">
                        @foreach($coas as $coa)
                            <thead style="background: #eee;">
                                <tr>
                                    <th></th>
                                    <th>No Voucher</th>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>{{$coa->code}}</td>
                                <th colspan="7">{{$coa->name}}</th>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    Select All <br />
                                    <input type="checkbox" wire:click="select_all({{$coa->id}})" />
                                </td>
                                <th colspan="6">Saldo Awal</th>
                                <td class="text-right">{{format_idr($coa->opening_balance)}}</td>
                            </tr>
                            @php($total_debit=0)
                            @php($total_kredit=0)
                            @php($total_saldo=$coa->opening_balance)
                            @foreach(\App\Models\Journal::where(['coa_id'=>$coa->id])->whereNull('general_ledger_id')->get() as $journal)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" wire:model="journal_id.{{$journal->id}}" value="1" wire:click="set_status_general_ledger({{$journal->id}})" />
                                    </td>
                                    <td>{{$journal->no_voucher}}</td>
                                    <td>{{date('d-M-Y',strtotime($journal->date_journal))}}</td>
                                    <td>{{isset($journal->coa->name)?$journal->coa->name : ''}}</td>
                                    <td>{{$journal->description}}</td>
                                    <td class="text-right">{{format_idr($journal->debit)}}</td>
                                    <td class="text-right">{{format_idr($journal->kredit)}}</td>
                                    <td class="text-right">{{format_idr($journal->saldo)}}</td>
                                </tr>
                                @php($total_debit +=$journal->debit)
                                @php($total_kredit +=$journal->kredit)
                                @php($total_saldo -=$journal->saldo)
                            @endforeach
                            @if(\App\Models\Journal::where(['coa_id'=>$coa->id])->count()==0)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                            <thead style="background: #eee;">
                                <tr>
                                    <th colspan="5" class="text-center">Total {{$coa->name}}</th>
                                    <th class="text-right">{{format_idr($total_debit)}}</th>
                                    <th class="text-right">{{format_idr($total_kredit)}}</th>
                                    <th class="text-right">{{format_idr($total_saldo)}}</th>
                                </tr>
                            </thead>
                            <tr>
                                <td colspan="9" class="py-2" style="border-left:0;border-right:0;"></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="submit_or_preview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width:90%;" role="document">
                @livewire('general-ledger.konven-create-preview')
            </div>
        </div>
        
    </div>
    @push('after-scripts')
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}"/>
    <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <style>
        .select2-container .select2-selection--single {height:36px;padding-left:10px;}
        .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
        .select2-container {width: 100% !important;}
    </style>
    <script>
        select__2 = $('.coa_group').select2();
        $('.coa_group').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('coa_group_id', data);
        });

        var selected__ = $('.coa_group').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);


        Livewire.on('added-journal',(msg)=>{
            toastr.remove();
            // toastr.options.timeOut = true;
            toastr['success'](msg, '', {
                    positionClass: 'toast-bottom-center'
                });
        });
    
        $('.journal_date').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("from_date", start.format('YYYY-MM-DD'));
            @this.set("to_date", end.format('YYYY-MM-DD'));
            $('.journal_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });
    </script>
    @endpush
</div>
