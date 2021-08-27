@section('title', 'Konven')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#underwriting">Underwriting</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reinsurance">Reinsurance</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#claim">Claim</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="underwriting">
                        <livewire:konven.underwriting />
                    </div>
                    <div class="tab-pane" id="claim">
                        <livewire:konven.claim />
                    </div>
                    <div class="tab-pane" id="reinsurance">
                        <livewire:konven.reinsurance />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>