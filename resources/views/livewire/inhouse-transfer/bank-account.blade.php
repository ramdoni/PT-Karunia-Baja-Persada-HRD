<div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control" wire:model="bank_account_id">
                    <option value=""> --- Bank Account --- </option>
                    @foreach(\App\Models\BankAccount::where('is_client',0)->get() as $bank)
                    <option value="{{ $bank->id}}">{{ $bank->owner }} - {{ $bank->no_rekening}} {{ $bank->bank}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 px-0">
            <a href="javascript:;" class="btn btn-info" data-toggle="modal" data-target="#modal_add_balance"><i class="fa fa-plus"></i> Balance</a>
            <div wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="col-md-7 text-right">
            <h6>Total Balance <span class="text-success"> Rp. {{format_idr($total_balance)}}</span></h6>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped m-b-0 c_list table-bordered table-style1 table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Type</th>
                    <th>Transaction Date</th>
                    <th>Bank Account</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $k => $item)
                <tr>
                    <td>{{$k+1}}</td>
                    <td>{{status_account_balance($item->type)}}</td>
                    <td>{{date('d-M-Y',strtotime($item->transaction_date))}}</td>
                    <td>{{isset($item->bank_account->no_rekening) ? $item->bank_account->no_rekening .'- '.$item->bank_account->bank.' an '. $item->bank_account->owner : '-'}}</td>
                    <td>{{format_idr($item->debit)}}</td>
                    <td>{{format_idr($item->kredit)}}</td>
                    <td>{{format_idr($item->nominal)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:inhouse-transfer.add-balance />
    </div>
</div>