@section('title', 'Report')
@section('parentPageTitle', 'Home')
<div class="row clearfix">
    <div class="col-lg-3 col-md-6">
        <div class="card overflowhidden">
            <div class="body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{format_idr(\App\Models\Income::where(['reference_type'=>'Premium Receivable','status'=>2,'is_auto'=>0])->sum('payment_amount'))}}</h5>
                    </div>
                    <div class="col-md-4 text-right" style="display: flex;justify-content: flex-end;align-items: flex-end;">
                        Unpaid
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <span>Premium Received</span>           
                    </div>
                    <div class="col-md-6 text-right">
                        <span class="text-danger">{{format_idr(\App\Models\Income::where(['reference_type'=>'Premium Receivable','status'=>1,'is_auto'=>0])->sum('nominal'))}}</span>        
                    </div>
                </div>
            </div>
            <div class="progress progress-xs progress-transparent custom-color-purple m-b-0">
                <div class="progress-bar" data-transitiongoal="67"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card overflowhidden">
            <div class="body">
                <h5>{{format_idr(\App\Models\Expenses::where('reference_type','Claim')->where('status',2)->sum('payment_amount'))}} <i class="fa fa-minus-circle float-right"></i></h5>
                <span>Claim</span>       
            </div>
            <div class="progress progress-xs progress-transparent custom-color-yellow m-b-0">
                <div class="progress-bar" data-transitiongoal="89"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card overflowhidden">
            <div class="body">
                <h5>{{format_idr(\App\Models\Income::where('status',2)->where('is_auto',0)->sum('payment_amount'))}} <i class="fa fa-plus-circle float-right"></i></h5>
                <span>Income</span>                            
            </div>
            <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                <div class="progress-bar" data-transitiongoal="64"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card overflowhidden">
            <div class="body">
                <h5>{{format_idr(\App\Models\Expenses::where('status',2)->sum('payment_amount'))}} <i class="fa fa-minus-circle float-right"></i></h5>
                <span>Expense</span>        
            </div>
            <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                <div class="progress-bar" data-transitiongoal="68"></div>
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#income">Income</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#expense">Expense</a></li>
                </ul>
                <div class="px-0 tab-content">
                    <div class="tab-pane show active" id="income">
                        <livewire:finance.income />
                    </div>
                    <div class="tab-pane" id="expense">
                        <livewire:finance.expense />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>