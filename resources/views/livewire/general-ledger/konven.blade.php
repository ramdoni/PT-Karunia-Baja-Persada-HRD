@section('title', 'Konven')
@section('parentPageTitle', 'General Ledger')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" class="form-control date" placeholder="Date Submited" />
                    </div>
                    <div class="col-md-8">
                        <a href="{{route('general-ledger.konven-create')}}" class="btn btn-info" title="Create General Ledger"><i class="fa fa-plus"></i> General Ledger</a>
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <hr />
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 c_list table-bordered">
                        <thead style="background: #eee;">
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Date Submited</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($data as $k => $item)
                            <tr>
                                <td>{{$data->currentPage()+$k}}</td>
                                <td>{{isset($item->user->name)?$item->user->name : ''}}</td>
                                <td>{{$item->submit_date}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    <a href="{{route('general-ledger.konven-detail',$item->id)}}" class="mx-2"><i class="fa fa-search"></i> Detail</a> 
                                    <a href="javascript:;" wire:click="downloadReport({{$item->id}})" title="Download Report"><i class="fa fa-download"></i> Report</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('after-scripts')
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}"/>
    <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <script>
        Livewire.on('added-journal',()=>{
            toastr.remove();
            toastr.options.timeOut = true;
            toastr['success']('Journal added', '', {
                    positionClass: 'toast-bottom-center'
                });
        });
    
        $('.date').daterangepicker({
            opens: 'left',
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
        }, function(start, end, label) {
            @this.set("from_date", start.format('YYYY-MM-DD'));
            @this.set("to_date", end.format('YYYY-MM-DD'));
            $('.date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

    </script>
    {{-- <script>
        $('body').addClass('layout-fullwidth');
		$(this).find(".fa").toggleClass('fa-arrow-left fa-arrow-right');
    </script> --}}
    @endpush
</div>
