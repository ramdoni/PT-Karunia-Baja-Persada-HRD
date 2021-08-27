<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Home;

date_default_timezone_set("Asia/Bangkok");
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', Home::class)->name('home')->middleware('auth');
Route::get('login', App\Http\Livewire\Login::class)->name('login');

// All login
Route::group(['middleware' => ['auth']], function(){    
    Route::get('profile',App\Http\Livewire\Profile::class)->name('profile');
    Route::get('back-to-admin',[App\Http\Controllers\IndexController::class,'backtoadmin'])->name('back-to-admin');
});

// Administrator
Route::group(['middleware' => ['auth','access:1']], function(){    
    Route::get('setting',App\Http\Livewire\Setting::class)->name('setting');
    Route::get('users/insert',App\Http\Livewire\User\Insert::class)->name('users.insert');
    Route::get('user-access', App\Http\Livewire\UserAccess\Index::class)->name('user-access.index');
    Route::get('user-access/insert', App\Http\Livewire\UserAccess\Insert::class)->name('user-access.insert');
    Route::get('users',App\Http\Livewire\User\Index::class)->name('users.index');
    Route::get('users/edit/{id}',App\Http\Livewire\User\Edit::class)->name('users.edit');
    Route::post('users/autologin/{id}',[App\Http\Livewire\User\Index::class,'autologin'])->name('users.autologin');
    Route::get('coa-group',App\Http\Livewire\CoaGroup\Index::class)->name('coa-group');
    Route::get('coa-group/insert',App\Http\Livewire\CoaGroup\Insert::class)->name('coa-group.insert');
    Route::get('coa-group/edit/{id}',App\Http\Livewire\CoaGroup\Edit::class)->name('coa-group.edit');
    Route::get('coa',App\Http\Livewire\Coa\Index::class)->name('coa');
    Route::get('coa/insert',App\Http\Livewire\Coa\Insert::class)->name('coa.insert');
    Route::get('coa/edit/{id}',App\Http\Livewire\Coa\Edit::class)->name('coa.edit');
    Route::get('coa-type',App\Http\Livewire\CoaType\Index::class)->name('coa-type');
    Route::get('coa-type/insert',App\Http\Livewire\CoaType\Insert::class)->name('coa-type.insert');
    Route::get('coa-type/edit/{id}',App\Http\Livewire\CoaType\Edit::class)->name('coa-type.edit');
    Route::get('journal',App\Http\Livewire\Journal\Index::class)->name('journal');
    Route::get('journal/download-excel',[App\Http\Livewire\Journal\Index::class,"downloadExcel"])->name('journal.download-excel');
    Route::get('bank-account',App\Http\Livewire\BankAccount\Index::class)->name('bank-account');
    Route::get('bank-account/insert',App\Http\Livewire\BankAccount\Insert::class)->name('bank-account.insert');
    Route::get('bank-account/edit/{id}',App\Http\Livewire\BankAccount\Edit::class)->name('bank-account.edit');
    Route::get('account-payable',App\Http\Livewire\AccountPayable\Index::class)->name('account-payable');
    Route::get('account-payable/insert',App\Http\Livewire\AccountPayable\Insert::class)->name('account-payable.insert');
    Route::get('account-payable/edit/{id}',App\Http\Livewire\AccountPayable\Edit::class)->name('account-payable.edit');
    Route::get('account-payable/view/{id}',App\Http\Livewire\AccountPayable\View::class)->name('account-payable.view');
    Route::get('account-receivable',App\Http\Livewire\AccountReceivable\Index::class)->name('account-receivable');
    Route::get('account-receivable/insert',App\Http\Livewire\AccountReceivable\Insert::class)->name('account-receivable.insert');
    Route::get('account-receivable/edit/{id}',App\Http\Livewire\AccountReceivable\Edit::class)->name('account-receivable.edit');
    Route::get('account-receivable/view/{id}',App\Http\Livewire\AccountReceivable\View::class)->name('account-receivable.view');
    Route::get('code-cashflow',App\Http\Livewire\CodeCashflow\Index::class)->name('code-cashflow');
    Route::get('code-cashflow/insert',App\Http\Livewire\CodeCashflow\Insert::class)->name('code-cashflow.insert');
    Route::get('code-cashflow/edit/{id}',App\Http\Livewire\CodeCashflow\Edit::class)->name('code-cashflow.edit');
    Route::get('data-teknis',App\Http\Livewire\DataTeknis\Index::class)->name('data-teknis');
    Route::get('policy',App\Http\Livewire\Policy\Index::class)->name('policy');
    Route::get('policy/insert',App\Http\Livewire\Policy\Insert::class)->name('policy.insert');
    Route::get('sales-tax',App\Http\Livewire\SalesTax\Index::class)->name('sales-tax');
    Route::get('sales-tax/insert',App\Http\Livewire\SalesTax\Insert::class)->name('sales-tax.insert');
    Route::get('sales-tax/edit/{id}',App\Http\Livewire\SalesTax\Edit::class)->name('sales-tax.edit');
    Route::get('konven-underwriting',App\Http\Livewire\Konven\Underwriting::class)->name('konven.underwriting');
    Route::get('konven-reinsurance',App\Http\Livewire\Konven\Reinsurance::class)->name('konven.reinsurance');
    Route::get('konven-claim',App\Http\Livewire\Konven\Claim::class)->name('konven.claim');
    Route::get('syariah-underwriting',App\Http\Livewire\Syariah\Index::class)->name('syariah.underwriting');
    Route::get('syariah-reinsurance',App\Http\Livewire\Syariah\Reinsurance::class)->name('syariah.reinsurance');
    Route::get('log-activity',App\Http\Livewire\LogActivity\Index::class)->name('log-activity');
    Route::get('migration',App\Http\Livewire\Migration\Index::class)->name('migration.index');
});

// Accounting
Route::group(['middleware' => ['auth','access:3']], function(){ 
    Route::get('accounting-journal',App\Http\Livewire\AccountingJournal\Index::class)->name('accounting-journal.index');
    Route::get('accounting-journal/detail/{id}',App\Http\Livewire\AccountingJournal\Detail::class)->name('accounting-journal.detail');
    Route::get('income',App\Http\Livewire\Income\Index::class)->name('income');
    Route::get('income/edit/{id}',App\Http\Livewire\Income\Edit::class)->name('income.edit');
    Route::get('expense',App\Http\Livewire\Expense\Index::class)->name('expense');
    Route::get('expense/edit/{id}',App\Http\Livewire\Expense\Edit::class)->name('expense.edit');
    Route::get('journal-penyesuaian',App\Http\Livewire\JournalPenyesuaian\Index::class)->name('journal-penyesuaian.index');
    Route::get('general-ledger',App\Http\Livewire\GeneralLedger\Index::class)->name('general-ledger.index');
    Route::get('general-ledger/detail/{id}',App\Http\Livewire\GeneralLedger\Detail::class)->name('general-ledger.detail');
    Route::get('general-ledger/revisi/{gl}',App\Http\Livewire\GeneralLedger\Revisi::class)->name('general-ledger.revisi');
    Route::get('general-ledger/revisi-history/{gl}',App\Http\Livewire\GeneralLedger\RevisiHistory::class)->name('general-ledger.revisi-history');
    Route::get('general-ledger/create/{coa_group}',App\Http\Livewire\GeneralLedger\Create::class)->name('general-ledger.create');
    Route::get('general-ledger-syariah',App\Http\Livewire\GeneralLedger\Syariah::class)->name('general-ledger.syariah');
    Route::get('general-ledger-konven',App\Http\Livewire\GeneralLedger\Konven::class)->name('general-ledger.konven');
    Route::get('general-ledger-konven/detail/{gl}',App\Http\Livewire\GeneralLedger\KonvenDetail::class)->name('general-ledger.konven-detail');
    Route::get('general-ledger-konven/create',App\Http\Livewire\GeneralLedger\KonvenCreate::class)->name('general-ledger.konven-create');
});

// Finance
Route::group(['middleware' => ['auth','access:2']], function(){    
    Route::get('trial-balance',App\Http\Livewire\TrialBalance\Index::class)->name('trial-balance');
    Route::get('cash-flow',App\Http\Livewire\CashFlow\Index::class)->name('cash-flow');
    Route::get('income-statement',App\Http\Livewire\IncomeStatement\Index::class)->name('income-statement');
    Route::get('balance-sheet',App\Http\Livewire\BalanceSheet\Index::class)->name('balance-sheet');
    Route::get('konven',App\Http\Livewire\Konven\Index::class)->name('konven');
    Route::get('konven/underwriting/detail/{id}',App\Http\Livewire\Konven\UnderwritingDetail::class)->name('konven.underwriting.detail');
    Route::get('operation',App\Http\Livewire\Operation\Index::class)->name('operation');
    Route::get('operation/payable/insert',App\Http\Livewire\Operation\PayableInsert::class)->name('operation.payable.insert');
    Route::get('operation/payable/edit/{id}',App\Http\Livewire\Operation\PayableEdit::class)->name('operation.payable.edit');
    Route::get('operation/receivable/insert',App\Http\Livewire\Operation\ReceivableInsert::class)->name('operation.receivable.insert');
    Route::get('operation/receivable/edit/{id}',App\Http\Livewire\Operation\ReceivableEdit::class)->name('operation.receivable.edit');
    Route::get('operation/expense/insert',App\Http\Livewire\Operation\ExpenseInsert::class)->name('operation.expense.insert');
    Route::get('operation/expense/edit/{id}',App\Http\Livewire\Operation\ExpenseEdit::class)->name('operation.expense.edit');
    Route::get('operation/income/insert',App\Http\Livewire\Operation\IncomeInsert::class)->name('operation.income.insert');
    Route::get('operation/income/edit/{id}',App\Http\Livewire\Operation\IncomeEdit::class)->name('operation.income.edit');
    Route::get('income-premium-receivable',App\Http\Livewire\IncomePremiumReceivable\Index::class)->name('income.premium-receivable');
    Route::get('income-premium-receivable/detail/{id}',App\Http\Livewire\IncomePremiumReceivable\Detail::class)->name('income.premium-receivable.detail');
    Route::get('income-reinsurance',App\Http\Livewire\IncomeReinsurance\Index::class)->name('income.reinsurance');
    Route::get('income-reinsurance/detail/{id}',App\Http\Livewire\IncomeReinsurance\Detail::class)->name('income.reinsurance.detail');
    Route::get('income-investment',App\Http\Livewire\IncomeInvesment\Index::class)->name('income.investment');
    Route::get('income-recovery-claim',App\Http\Livewire\IncomeRecoveryClaim\Index::class)->name('income.recovery-claim');
    Route::get('income-recovery-claim/insert',App\Http\Livewire\IncomeRecoveryClaim\Insert::class)->name('income.recovery-claim.insert');
    Route::get('income-recovery-claim/detail/{id}',App\Http\Livewire\IncomeRecoveryClaim\Detail::class)->name('income.recovery-claim.detail');
    Route::get('income-recovery-refund',App\Http\Livewire\IncomeRecoveryRefund\Index::class)->name('income.recovery-refund');
    Route::get('income-recovery-refund/insert',App\Http\Livewire\IncomeRecoveryRefund\Insert::class)->name('income.recovery-refund.insert');
    Route::get('income-recovery-refund/detail/{id}',App\Http\Livewire\IncomeRecoveryRefund\Detail::class)->name('income.recovery-refund.detail');
    Route::get('expense-reinsurance-premium',App\Http\Livewire\ExpenseReinsurance\Index::class)->name('expense.reinsurance-premium');
    Route::get('expense-reinsurance-premium/detail/{id}',App\Http\Livewire\ExpenseReinsurance\Detail::class)->name('expense.reinsurance-premium.detail');
    Route::get('expense-claim',App\Http\Livewire\ExpenseClaim\Index::class)->name('expense.claim');
    Route::get('expense-claim/detail/{id}',App\Http\Livewire\ExpenseClaim\Detail::class)->name('expense.claim.detail');
    Route::get('expense-claim/insert',App\Http\Livewire\ExpenseClaim\Insert::class)->name('expense.claim.insert');
    Route::get('expense-others',App\Http\Livewire\ExpenseOthers\Index::class)->name('expense.others');
    Route::get('expense-others/detail/{id}',App\Http\Livewire\ExpenseOthers\Detail::class)->name('expense.others.detail');
    Route::get('expense-others/insert',App\Http\Livewire\ExpenseOthers\Insert::class)->name('expense.others.insert');
    Route::get('expense-commision-payable',App\Http\Livewire\ExpenseCommisionPayable\Index::class)->name('expense.commision-payable');
    Route::get('expense-commision-payable/insert',App\Http\Livewire\ExpenseCommisionPayable\Insert::class)->name('expense.commision-payable.insert');
    Route::get('expense-commision-payable/detail/{id}',App\Http\Livewire\ExpenseCommisionPayable\Detail::class)->name('expense.commision-payable.detail');
    Route::get('expense-endorsement',App\Http\Livewire\ExpenseEndorsement\Index::class)->name('expense-endorsement');
    Route::get('expense-endorsement/detail/{id}',App\Http\Livewire\ExpenseEndorsement\Detail::class)->name('expense-endorsement.detail');
    Route::get('expense-cancelation',App\Http\Livewire\ExpenseCancelation\Index::class)->name('expense-cancelation');
    Route::get('expense-cancelation/detail/{id}',App\Http\Livewire\ExpenseCancelation\Detail::class)->name('expense-cancelation.detail');
    Route::get('expense-refund',App\Http\Livewire\ExpenseRefund\Index::class)->name('expense-refund');
    Route::get('expense-refund/detail/{id}',App\Http\Livewire\ExpenseRefund\Detail::class)->name('expense-refund.detail');
    Route::get('expense-handling-fee',App\Http\Livewire\ExpenseHandlingFee\Index::class)->name('expense-handling-fee');
    Route::get('expense-handling-fee/detail/{id}',App\Http\Livewire\ExpenseHandlingFee\Detail::class)->name('expense-handling-fee.detail');
    Route::get('endorsement',App\Http\Livewire\Endorsement\Index::class)->name('endorsement.index');
    Route::get('endorsement/dn-insert-reas',App\Http\Livewire\Endorsement\DnInsertReas::class)->name('endorsement.dn-insert-reas');
    Route::get('endorsement/dn-detail-reas/{id}',App\Http\Livewire\Endorsement\DnDetailReas::class)->name('endorsement.dn-detail-reas');
    Route::get('endorsement/dn-detail/{id}',App\Http\Livewire\Endorsement\DnDetail::class)->name('endorsement.dn-detail');
    Route::get('endorsement/cn-detail/{id}',App\Http\Livewire\Endorsement\CnDetail::class)->name('endorsement.cn-detail');
    Route::get('endorsement/cn-insert-reas',App\Http\Livewire\Endorsement\CnInsertReas::class)->name('endorsement.cn-insert-reas');
    Route::get('endorsement/cn-detail-reas/{id}',App\Http\Livewire\Endorsement\CnDetailReas::class)->name('endorsement.cn-detail-reas');
    Route::get('income-titipan-premi',App\Http\Livewire\IncomeTitipanPremi\Index::class)->name('income.titipan-premi');
    Route::get('income-titipan-premi/insert',App\Http\Livewire\IncomeTitipanPremi\Insert::class)->name('income.titipan-premi.insert');
    Route::get('income-titipan-premi/detail/{id}',App\Http\Livewire\IncomeTitipanPremi\Detail::class)->name('income.titipan-premi.detail');
    Route::get('finance',App\Http\Livewire\Finance\Index::class)->name('finance.index');
    Route::get('others-income',App\Http\Livewire\OthersIncome\Index::class)->name('others-income.index');
    Route::get('others-income/insert',App\Http\Livewire\OthersIncome\Insert::class)->name('others-income.insert');
    Route::get('others-income/detail/{id}',App\Http\Livewire\OthersIncome\Detail::class)->name('others-income.detail');
    // Route::get('policy',App\Http\Livewire\Policy\Index::class)->name('policy');
    // Route::get('policy/insert',App\Http\Livewire\Policy\Insert::class)->name('policy.insert');
});
// Treasury
Route::group(['middleware' => ['auth','access:4']], function(){ 
    Route::get('treasury/index',App\Http\Livewire\Treasury\Index::class)->name('treasury.index');
    Route::get('inhouse-transfer',App\Http\Livewire\InhouseTransfer\Index::class)->name('inhouse-transfer.index');
    Route::get('bank-account-company',App\Http\Livewire\Treasury\BankAccountCompany\Index::class)->name('bank-account-company');
    Route::get('bank-account-company/insert',App\Http\Livewire\Treasury\BankAccountCompany\Insert::class)->name('bank-account-company.insert');
    Route::get('bank-account-company/edit/{id}',App\Http\Livewire\Treasury\BankAccountCompany\Edit::class)->name('bank-account-company.edit');
});