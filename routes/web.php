<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ExternalLoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LoanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
//Admin group middleware
Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');

    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');  
    
    
    Route::get('admin/users', [AdminController::class, 'GetUsers'])
                ->name('admin.users');
    // get page one of registration page
    Route::get('admin/registeruser/one', [RegisteredUserController::class, 'create'])
                ->name('admin.register.one');
    // Post page one data
    Route::post('admin/registeruser/post/one', [RegisteredUserController::class, 'CreateOne'])->name('admin.registeruser.post.one');
    // Get page two of reg page
    Route::get('admin/registeruser/two', [RegisteredUserController::class, 'CreatePageTwo'])
                ->name('admin.register.two');
    // Post page two data
    Route::post('admin/registeruser/post/two', [RegisteredUserController::class, 'CreateTwo'])->name('admin.registeruser.post.two');
    // Get page three reg page
    Route::get('admin/registeruser/three', [RegisteredUserController::class, 'CreatePageThree'])
                ->name('admin.register.three');
    // Post page three data
    Route::post('admin/registeruser/post/three', [RegisteredUserController::class, 'CreateThree'])->name('admin.registeruser.post.three');

    // Post page two data
   

   
    Route::post('admin/user/view', [AdminController::class, 'ViewUser'])->name('admin.user.view');

    Route::post('admin/user/disable', [AdminController::class, 'DisableUser'])->name('admin.user.disable');

    // Admin users depodits
    Route::get('admin/user/deposit', [PaymentController::class, 'Deposit'])->name('admin.user.deposit');
    
    Route::post('admin/user/update', [RegisteredUserController::class, 'UpdateUser'])->name('admin.user.update');

    Route::post('admin/user/remove_payer', [PaymentController::class, 'RemoveUserPayment'])->name('admin.user.remove_payer');

    Route::get('admin/user/complete_payments', [PaymentController::class, 'CompletePayment'])->name('admin.user.complete_payments');    

    //year plan
    Route::get('admin/config/year_plan', [AdminController::class, 'NewYearPlan'])->name('admin.config.year_plan');    

    Route::post('admin/config/open_year', [AdminController::class, 'OpenYear'])->name('admin.config.open_year');

    Route::get('admin/config/year_close', [AdminController::class, 'CloseYearView'])->name('admin.config.year_close');

    Route::get('admin/config/year/close', [AdminController::class, 'CloseYear'])->name('admin.config.year.close');

    Route::get('admin/config/liabilities', [AdminController::class, 'Liability'])->name('admin.config.liabilities');

    Route::post('admin/config/save_liability', [AdminController::class, 'SaveLiability'])->name('admin.config.save_liability');

    //loans
    Route::get('loan/approve_loan', [LoanController::class, 'ApproveLoan'])->name('loan.approve_loan');
    Route::post('loan/approve', [LoanController::class, 'ApproveLoanApplication'])->name('loan.approve'); 

    Route::get('admin/loan/repayment', [LoanController::class, 'OpenLoanRepayment'])->name('admin.loan.repayment');

    Route::post('loan/remove_loan', [LoanController::class, 'RemoveLoan'])->name('loan.remove_loan');

    Route::get('loan/complete_repayment', [LoanController::class, 'CompletePayment'])->name('loan.complete_repayment');
    
    //external loan (secretary and admin)
    Route::post('loan/external', [ExternalLoanController::class, 'Create'])->name('loan.external');
    Route::post('loan/external/apply', [ExternalLoanController::class, 'Apply'])->name('loan.external.apply');
    Route::get('loan/external/approval', [ExternalLoanController::class, 'OpenApproval'])->name('loan.external.approval');

     Route::post('loan/external/approve', [ExternalLoanController::class, 'ApproveLoan'])->name('loan.external.approve');

     Route::get('loan/external/repayment', [ExternalLoanController::class, 'Repayment'])->name('loan.external.repayment');

     Route::get('loan/external/complete_repayment', [ExternalLoanController::class, 'CompletePayment'])->name('loan.external.complete_repayment');

    
    
});// End admin route group




Route::middleware(['auth', 'role:agent'])->group(function(){
    Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])->name('agent.dashboard');
});//End Agent route group

//Admin login
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');

Route::get('/loan/apply', [LoanController::class, 'LoanApplication'])->name('loan.apply');

Route::post('/loan/finish_application', [LoanController::class, 'ApplyLoan'])->name('loan.finish_application');







