<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            @if (\Auth::user()->profile_photo_path != '')
                <img src="{{ \Auth::user()->profile_photo_path }}" class="rounded-circle user-photo"
                    alt="User Profile Picture">
            @endif
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name"
                    data-toggle="dropdown"><strong>{{ isset(\Auth::user()->name) ? \Auth::user()->name : '' }}</strong></a>
                <ul class="dropdown-menu dropdown-menu-right account">
                    <li><a href="{{ route('profile') }}"><i class="icon-user"></i>My Profile</a></li>
                    <li><a href="{{ route('setting') }}"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>
            <hr>
        </div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu"><i
                        class="fa fa-database"></i> Data</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">
                        @if (\Auth::user()->user_access_id == 1)
                            <!--Administrator-->
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="/"><i class="fa fa-home"></i> <span>Dashboard</span></a>
                            </li>
                            <li
                                class="{{ Request::segment(1) === 'konven-underwriting' || Request::segment(1) === 'konven-reinsurance' || Request::segment(1) === 'konven-claim' ? 'active' : null }}">
                                <a href="#App" class="has-arrow"><i class="fa fa-database"></i> <span>Konven</span></a>
                                <ul>
                                    <li class="{{ Request::segment(1) === 'konven-underwriting' ? 'active' : null }}">
                                        <a href="{{ route('konven.underwriting') }}">Underwriting</a>
                                    </li>
                                    <li class="{{ Request::segment(1) === 'konven-reinsurance' ? 'active' : null }}">
                                        <a href="{{ route('konven.reinsurance') }}">Reinsurance</a>
                                    </li>
                                    {{-- <li class="{{ Request::segment(1) === 'konven-claim' ? 'active' : null }}"><a href="{{route('konven.claim')}}">Claim</a></li> --}}
                                </ul>
                            </li>
                            <li
                                class="{{ Request::segment(1) === 'syariah-underwriting' || Request::segment(1) === 'syariah-reinsurance' ? 'active' : null }}">
                                <a href="#App" class="has-arrow"><i class="fa fa-database"></i> <span>Syariah</span></a>
                                <ul>
                                    <li
                                        class="{{ Request::segment(1) === 'syariah-underwriting' ? 'active' : null }}">
                                        <a href="{{ route('syariah.underwriting') }}">Underwriting</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'syariah-reinsurance' ? 'active' : null }}">
                                        <a href="{{ route('syariah.reinsurance') }}">Reinsurance</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ Request::segment(1) === 'users' ? 'active' : null }}">
                                <a href="{{ route('users.index') }}"><i class="fa fa-users"></i>
                                    <span>Users</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'sales-tax' ? 'active' : null }}">
                                <a href="{{ route('sales-tax') }}"><i class="fa fa-database"></i> <span>Sales
                                        Tax</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'policy' ? 'active' : null }}">
                                <a href="{{ route('policy') }}"><i class="fa fa-database"></i> <span>Polis</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'journal' ? 'active' : null }}">
                                <a href="{{ route('bank-account') }}"><i class="fa fa-database"></i> <span>Bank
                                        Account</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'code-cashflow' ? 'active' : null }}">
                                <a href="{{ route('code-cashflow') }}"><i class="fa fa-database"></i> <span>Code
                                        Cashflow</span></a>
                            </li>
                            <li
                                class="{{ Request::segment(1) === 'coa' || Request::segment(1) === 'coa-group' || Request::segment(1) === 'coa-type' ? 'active' : null }}">
                                <a href="#App" class="has-arrow"><i class="fa fa-database"></i> <span>COA</span></a>
                                <ul>
                                    <li class="{{ Request::segment(1) === 'coa' ? 'active' : null }}"><a
                                            href="{{ route('coa') }}">COA</a></li>
                                    <li class="{{ Request::segment(1) === 'coa-group' ? 'active' : null }}"><a
                                            href="{{ route('coa-group') }}">COA Groups</a></li>
                                </ul>
                            </li>
                            <li class="{{ Request::segment(1) === 'log-activity' ? 'active' : null }}">
                                <a href="{{ route('log-activity') }}"><i class="fa fa-history"></i> <span>Log
                                        Activity</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 2)
                            <!--Finance-->
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="/"><i class="fa fa-home"></i> <span>Dashboard</span></a>
                            </li>
                            <!-- <li class="{{ Request::segment(1) === 'policy' ? 'active' : null }}">
                                <a href="{{ route('policy') }}"><i class="fa fa-database"></i> <span>Polis</span></a>
                            </li> -->
                            <li
                                class="{{ Request::segment(1) === 'others-income' || Request::segment(1) === 'income-recovery-refund' || Request::segment(1) === 'income-recovery-claim' || Request::segment(1) === 'income-titipan-premi' || Request::segment(1) === 'income-premium-receivable' || Request::segment(1) === 'income-reinsurance' || Request::segment(1) === 'income-investment' ? 'active' : null }}">
                                <a href="#" class="has-arrow"><i class="fa fa-database"></i> <span>Income</span></a>
                                <ul>
                                    <li
                                        class="{{ Request::segment(1) === 'income-premium-receivable' ? 'active' : null }}">
                                        <a href="{{ route('income.premium-receivable') }}"> Premium Receivable</a>
                                    </li>
                                    <li class="{{ Request::segment(1) === 'income-reinsurance' ? 'active' : null }}">
                                        <a href="{{ route('income.reinsurance') }}"> Reinsurance Commision</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'income-recovery-claim' ? 'active' : null }}">
                                        <a href="{{ route('income.recovery-claim') }}"> Recovery Claim</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'income-recovery-refund' ? 'active' : null }}">
                                        <a href="{{ route('income.recovery-refund') }}"> Recovery Refund</a>
                                    </li>
                                    <li class="{{ Request::segment(1) === 'income-investment' ? 'active' : null }}">
                                        <a href="{{ route('income.investment') }}"> Invesment</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'income-titipan-premi' ? 'active' : null }}">
                                        <a href="{{ route('income.titipan-premi') }}"> Premium Deposit</a>
                                    </li>
                                    <li class="{{ Request::segment(1) === 'others-income' ? 'active' : null }}"><a
                                            href="{{ route('others-income.index') }}"> Others Income</a></li>
                                </ul>
                            </li>
                            <li
                                class="{{ Request::segment(1) === 'expense-refund' || Request::segment(1) === 'expense-handling-fee' || Request::segment(1) === 'expense-cancelation' || Request::segment(1) === 'expense-endorsement' || Request::segment(1) === 'expense-claim' || Request::segment(1) === 'expense-others' || Request::segment(1) === 'expense-reinsurance-premium' || Request::segment(1) === 'expense-commision-payable' ? 'active' : null }}">
                                <a href="#" class="has-arrow"><i class="fa fa-database"></i> <span>Expense</span></a>
                                <ul>
                                    <li
                                        class="{{ Request::segment(1) === 'expense-reinsurance-premium' ? 'active' : null }}">
                                        <a href="{{ route('expense.reinsurance-premium') }}"> Reinsurance Premium</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'expense-commision-payable' ? 'active' : null }}">
                                        <a href="{{ route('expense.commision-payable') }}"> Commision Payable</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'expense-cancelation' ? 'active' : null }}">
                                        <a href="{{ route('expense-cancelation') }}"> Cancelation</a>
                                    </li>
                                    <li class="{{ Request::segment(1) === 'expense-refund' ? 'active' : null }}"><a
                                            href="{{ route('expense-refund') }}"> Refund</a></li>
                                    <li class="{{ Request::segment(1) === 'expense-claim' ? 'active' : null }}"><a
                                            href="{{ route('expense.claim') }}"> Claim Payable</a></li>
                                    <li class="{{ Request::segment(1) === 'expense-others' ? 'active' : null }}"><a
                                            href="{{ route('expense.others') }}"> Others Expense</a></li>
                                    <li
                                        class="{{ Request::segment(1) === 'expense-handling-fee' ? 'active' : null }}">
                                        <a href="{{ route('expense-handling-fee') }}"> Handling Fee</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ Request::segment(1) === 'endorsement' ? 'active' : null }}">
                                <a href="{{ route('endorsement.index') }}"><i class="fa fa-database"></i>
                                    <span>Endorsement</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 3)
                            <!--Accounting-->
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="{{ route('accounting-journal.index') }}"><i class="fa fa-home"></i>
                                    <span>Journal</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="{{ route('journal-penyesuaian.index') }}"><i class="fa fa-edit"></i>
                                    <span>Adjusting</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'general-ledger' ? 'active' : null }}">
                                <a href="{{ route('general-ledger.index') }}"><i class="fa fa-table"></i>
                                    <span>General Ledger</span></a>
                                {{-- class="{{ (Request::segment(1) === 'general-ledger-syariah' || Request::segment(1) === 'general-ledger-konven')?'active':''}}">
                                <a href="#" class="has-arrow"><i class="fa fa-table"></i> <span>General Ledger</span></a>
                                <ul>
                                    <li
                                        class="{{ Request::segment(1) === 'general-ledger-syariah' ? 'active' : null }}">
                                        <a href="{{ route('general-ledger.syariah') }}"> Syariah</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'general-ledger-konven' ? 'active' : null }}">
                                        <a href="{{ route('general-ledger.konven') }}"> Konven</a>
                                    </li>
                                </ul> --}}
                            </li>
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="#"><i class="fa fa-database"></i> <span>Cashflow</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="#"><i class="fa fa-database"></i> <span>Trial Balance</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="#"><i class="fa fa-database"></i> <span>Income Statement</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="#"><i class="fa fa-database"></i> <span>Balance Sheet</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 4)
                            <!--Treasury-->
                            <li class="{{ Request::segment(1) === 'inhouser-transfer' ? 'active' : null }}">
                                <a href="{{ route('inhouse-transfer.index') }}"><i class="fa fa-home"></i>
                                    <span>Inhouse Transfer</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'journal' ? 'active' : null }}">
                                <a href="{{ route('bank-account-company') }}"><i class="fa fa-database"></i>
                                    <span>Bank
                                        Account</span></a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
