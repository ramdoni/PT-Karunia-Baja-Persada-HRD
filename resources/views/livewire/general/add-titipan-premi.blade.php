<div wire:ignore.self class="modal fade" id="modal_add_titipan_premi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width:90%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Premium Deposit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <form wire:submit.prevent="save">
                <div class="row p-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-3" wire:ignore>
                        <select class="form-control titipan_from_bank_account" wire:model="from_bank_account_id">
                            <option value=""> --- {{__('To Bank Account')}} --- </option>
                            @foreach (\App\Models\BankAccount::where('is_client',1)->orderBy('owner','ASC')->get() as $bank)
                                <option value="{{ $bank->id}}">{{ $bank->bank}} - {{ $bank->no_rekening}} - {{ $bank->owner }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3" wire:ignore>
                        <select class="form-control titipan_to_bank_account" wire:model="to_bank_account_id">
                            <option value=""> --- {{__('To Bank Account')}} --- </option>
                            @foreach (\App\Models\BankAccount::where('is_client',0)->orderBy('owner','ASC')->get() as $bank)
                                <option value="{{ $bank->id}}">{{ $bank->bank}} - {{ $bank->no_rekening}} - {{ $bank->owner }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div wire:loading class="mt-1 ml-3">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="modal-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-b-0 c_list">
                            <thead>
                                <tr>
                                    <th></th>                                    
                                    <th>No Voucher</th>                                    
                                    <th>Payment Date</th>                                    
                                    <th>Voucher Date</th>  
                                    <th>Reference No</th>                                      
                                    <th>From Bank Account</th>
                                    <th>To Bank Account</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $k => $item)
                                <tr>
                                    <td><a href="javascript:void(0)" class="badge badge-success" wire:click="$emit('set-titipan-premi',{{$item->id}})"><i class="fa fa-arrow-right"></i> Select</a></td>
                                    <td><a href="{{route('income.titipan-premi.detail',$item->id)}}" target="_blank">{!! no_voucher($item) !!}</a></td>
                                    <td>{{$item->payment_date?date('d M Y', strtotime($item->payment_date)):'-'}}</td>
                                    <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                                    <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                                    <td>{{isset($item->from_bank_account->no_rekening) ? $item->from_bank_account->bank.'-'.$item->from_bank_account->no_rekening .' an '. $item->from_bank_account->owner : '-'}}</td>
                                    <td>{{isset($item->bank_account->no_rekening) ? $item->bank_account->bank .' - '. $item->bank_account->no_rekening .' an '. $item->bank_account->owner : '-'}}</td>
                                    <td>{{format_idr($item->nominal - $item->titipan_premi->sum('nominal'))}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br />
                    {{$data->links()}}
                </div>
                <div class="modal-footer">
                    <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Close</a>
                </div>
            </form>
        </div>
        @push('after-scripts')
        <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
        <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
        <style>
            .select2-container .select2-selection--single {height:36px;padding-left:10px;}
            .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
            .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
            .select2-container {width: 100% !important;}
        </style>
        <script>
            select__2 = $('.titipan_from_bank_account').select2();
            $('.titipan_from_bank_account').on('change', function (e) {
                var data = $(this).select2("val");
                @this.set('from_bank_account_id', data);
            });
            var selected__ = $('.titipan_from_bank_account').find(':selected').val();
            if(selected__ !="") select__2.val(selected__);
            
            Livewire.on('set-titipan-premi',(id)=>{
                $("#modal_add_titipan_premi").modal("hide");
            });
        </script>
        @endpush
    </div>
</div>
