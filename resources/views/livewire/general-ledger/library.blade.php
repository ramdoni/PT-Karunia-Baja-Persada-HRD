<div>
    <div class="row">
        <div class="col-md-3" wire:ignore>
            <select class="form-control" wire:model="coa_group_id" id="coa_group_id">
                <option value=""> --- Categories --- </option>
                @foreach(\App\Models\CoaGroup::orderBy('name','ASC')->get() as $coa)
                <option value="{{$coa->id}}">{{$coa->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="year">
                <option value=""> --- Year --- </option>
                @foreach(\App\Models\GeneralLedger::groupBy('year')->get() as $gl)
                <option>{{$gl->year}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" wire:model="month">
                <option value=""> --- Month --- </option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
        <div class="col-md-5">
            <span wire:loading>
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">{{ __('Loading...') }}</span>
            </span>
        </div>
    </div>
    <hr />
    <div class="table-responsive">
        <table class="table table-striped m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Number</th>
                    <th>Categories</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @php($num=$library->firstItem())
            @foreach($library as $item)
                <tr>
                    <td>{{$num}}</td>
                    <td><a href="{{route('general-ledger.detail',$item->id)}}">{{gl_number($item)}}</a></td>
                    <td>{{isset($item->coa_group->name) ? $item->coa_group->name : ''}}</td>
                    <td>{{date('F', mktime(0, 0, 0, $item->month, 10))}}</td>
                    <td>{{$item->year}}</td>
                    <td>
                        <a href="{{route('general-ledger.revisi',$item->id)}}" title="Revisi"><i class="fa fa-edit"></i></a>
                        <a href="javascript::void(0);" wire:click="downloadReport({{$item->id}})" title="Download Report" class="text-info"><i class="fa fa-download"></i></a>
                    </td>
                </tr>
                @php($num++)
            @endforeach
            </tbody>
        </table>
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
        select__2 = $('#coa_group_id').select2();
        $('#coa_group_id').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('coa_group_id', data);
        });
    </script>
    @endpush
</div>