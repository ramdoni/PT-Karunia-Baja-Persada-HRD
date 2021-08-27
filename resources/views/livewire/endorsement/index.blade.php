@section('title', 'Endorsement')
@section('parentPageTitle', 'Dashboard')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Debit Note</h6>
                            <span class="total_debit_note"></span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Credit Note</h6>
                            <span class="total_credit_note"></span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#db">Debit Note</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cn">Credit Note</a></li>
                </ul>
                <div class="px-0 tab-content">
                    <div class="tab-pane show active" id="db">
                        <livewire:endorsement.dn />
                    </div>
                    <div class="tab-pane" id="cn">
                        <livewire:endorsement.cn />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>