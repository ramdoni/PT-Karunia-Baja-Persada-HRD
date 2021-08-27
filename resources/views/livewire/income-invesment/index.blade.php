@section('title', 'Investment')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
                    <div class="col-md-2">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="px-0 col-md-1">
                        <select class="form-control" wire:model="status">
                            <option value=""> --- Status --- </option>
                            <option value="1"> Unpaid </option>
                            <option value="2"> Paid</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>                                    
                                <th>Status</th>                                    
                                <th>No Voucher</th>                                    
                                <th>Client</th>                                    
                                <th>Reference Type</th>                                    
                                <th>Reference No</th>                                    
                                <th>Reference Date</th>
                                <th>Description</th>
                                <th>Tax Inclusive Amount</th>
                                <th>Tax Code</th>
                                <th>Exclusive Amount</th>
                                <th>Tax Amount</th>
                                <th>Outstanding Balance</th>
                                <th>Payment Amount</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                      
                        </tbody>
                    </table>
                </div>
                <br />
            </div>
        </div>
    </div>
</div>