@section('title', 'Syariah')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#underwriting">Underwriting</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#endorsement">Endorsement</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cancel">Cancel</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#refund">Refund</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="underwriting">
                        <livewire:syariah.underwriting />
                    </div>
                    <div class="tab-pane" id="endorsement">
                        <livewire:syariah.endorsement />
                    </div>
                    <div class="tab-pane" id="cancel">
                        <livewire:syariah.cancel />
                    </div>
                    <div class="tab-pane" id="refund">
                        <livewire:syariah.refund />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>