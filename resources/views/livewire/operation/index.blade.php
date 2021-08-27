@section('title', 'Operation')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#expense">Expense</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#income">Income</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="expense">
                        <livewire:operation.expense />
                    </div>
                    <div class="tab-pane" id="income">
                        <livewire:operation.income />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>