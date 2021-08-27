@section('title', __('Report'))
@section('parentPageTitle', __('Dashboard'))
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#inhouse-transfer">{{ __('Inhouse Transfer') }}</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#bank-account">{{ __('Balance') }}</a></li>
                </ul>
                <div class="px-0 tab-content">
                    <div class="tab-pane show active" id="inhouse-transfer">
                        <div class="mb-3 row">
                            <div class="col-md-2">
                                <select class="form-control">
                                    <option value=""> --- From Bank Account --- </option>
                                    @foreach(\App\Models\BankAccount::where('is_client',0)->get() as $bank)
                                    <option value="{{ $bank->id}}">{{ $bank->owner }} - {{ $bank->no_rekening}} {{ $bank->bank}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="px-0 col-md-2">
                                <select class="form-control">
                                    <option value=""> --- To Bank Account --- </option>
                                    @foreach(\App\Models\BankAccount::where('is_client',0)->get() as $bank)
                                    <option value="{{ $bank->id}}">{{ $bank->owner }} - {{ $bank->no_rekening}} {{ $bank->bank}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" />
                            </div>
                            <div class="">
                                <a href="javascript:;" data-toggle="modal" data-target="#modal_add_inhouse_transfer" class="btn btn-info"><i class="fa fa-plus"></i> Inhouse Transfer</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped m-b-0 c_list table-bordered table-style1 table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>From Bank Account</th>
                                        <th>To Bank Account</th>
                                        <th>Nominal</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $k => $item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>{{isset($item->from_bank_account->no_rekening) ? $item->from_bank_account->no_rekening .'- '.$item->from_bank_account->bank.' an '. $item->from_bank_account->owner : '-'}}</td>
                                        <td>{{isset($item->to_bank_account->no_rekening) ? $item->to_bank_account->no_rekening .'- '.$item->to_bank_account->bank.' an '. $item->to_bank_account->owner : '-'}}</td>
                                        <td>{{format_idr($item->nominal)}}</td>
                                        <td>{{date('d-M-Y',strtotime($item->transaction_date))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="bank-account">
                        <livewire:inhouse-transfer.bank-account />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_inhouse_transfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:inhouse-transfer.add />
    </div>
</div>
@section('page-script')
{{-- $( document ).ready(function() {
    $(".btn-toggle-fullwidth").trigger('click');
}); --}}
Livewire.on('emit-add-form-hide',()=>{
    $("#modal_add_inhouse_transfer").modal("hide");
});
Livewire.on('emit-add-balance-hide',()=>{
    $("#modal_add_balance").modal("hide");
});
@endsection