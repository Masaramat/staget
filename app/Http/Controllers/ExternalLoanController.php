<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ExternalLoan;
use App\Models\LoanApplication;
use App\Models\StagetAccount;
use App\Models\YearPlan;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExternalLoanController extends Controller
{
    
    public function Create(Request $request){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
       $account = Account::join('users', 'users.id', '=', 'accounts.user_id')
            ->where('accounts.user_id', $request->guarantor)
            ->first(['accounts.*', 'users.name']);
       
        if($year_plan === null){
            $notification = array(
                'message' => 'There is no active year plan!',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }
        $id = $request->guarantor;
        return view('admin.admin_external_loan', compact('year_plan', 'account', 'id'));
    }

    public function Apply(Request $request){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $account = Account::where('user_id', $request->guarantor_id)->first();
        $maxValue = $account->total_savings * ($year_plan->loan_percentage/100);
        $data = $request->validate([            
            'amount_applied' => 'numeric|max:'.$maxValue,
            'tenor' => 'required|string',
            'repayment_type' => 'required'
        ]);

        $loanApplication = new LoanApplication();
        $loanApplication->fill($data);
        $loanApplication->tenor_type = 'months';
        $loanApplication->year_id = $year_plan->id;
        $loanApplication->user_id = $account->user_id;
        $loanApplication->loan_type = 'external';

        $loanApplication->save();
        $external_loan_details =  array(
            'borrower_name' => $request->applicant_name,
            'borrower_bvn' =>$request->applicant_bvn,
            'borrower_phone' =>$request->applicant_phone,
            'loan_id' =>$loanApplication->id
        );
        ;
        if(DB::table('external_borrowers')->insert($external_loan_details)){
             $notification = array(
                'message' => 'Loan application sccessful!',
                'alert-type' => 'success'
            );

        }else{
             $notification = array(
                'message' => 'Something went wrong, try again',
                'alert-type' => 'error'
            );
        }
        
        return redirect()->route('admin.dashboard')->with($notification);
    }

    public function OpenApproval(){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $loans = DB::table('loan_applications')
            ->join('users', function(JoinClause $joinClause){
                $joinClause->on('users.id', '=', 'loan_applications.user_id')
                    ->where('loan_applications.application_status', 'pending')
                    ->where('loan_applications.loan_type', 'external');
            })->join('external_borrowers', function(JoinClause $joinClause){
                $joinClause->on('external_borrowers.loan_id', '=', 'loan_applications.id');
            })
            ->select('loan_applications.*', 'users.name', 'external_borrowers.borrower_name', 'external_borrowers.borrower_bvn', 'external_borrowers.borrower_phone')
            ->get();
        if($year_plan === null){
             $notification = array(
                'message' => 'There is no active year plan!',
                'alert-type' => 'error'
            );

            return back()->with($notification);

        }
        $xy = 1;
        return view('admin.admin_approve_external_loan', compact('loans', 'xy'));
    }

    public function ApproveLoan(Request $request){
        $loan = LoanApplication::where('id', '=', $request->id)->first();
        $year_plan = YearPlan::where('status', '=', 'open')->first();

        if($request->has('approve')){            
            $data = $request->validate([
                'amount_approved' => 'required|numeric',
                'tenor_approved' => 'required|string'
            ]);

            $loan->fill($data);            
            $loan->application_status = 'open';
            $interest_type = $year_plan->interest_type;

            if($interest_type === 'monthly'){
                $interest = $request->amount_approved * ($year_plan->external_interest/100) * $request->tenor_approved;
            }else if($interest_type === 'one_off'){
                $interest = $request->amount_approved * ($year_plan->external_interest/100);
            }

            if($loan->repayment_type == 'balloon upfront interest'){                
                $loan->installments = $request->amount_approved;
                $loan->balance = $request->amount_approved;
                $loan->commission_paid = ($year_plan->external_commission/100) * $interest;
                $loan->interest_paid = $interest - $loan->commission_paid;
            }else if($loan->repayment_type == 'flat upfront interest'){
                $loan->installments = $request->amount_approved / $request->tenor_approved;
                $loan->balance = $request->amount_approved;
                $loan->commission_paid = ($year_plan->external_commission/100) * $interest;
                $loan->interest_paid = $interest - $loan->commission_paid;
            }else if($loan->repayment_type == 'flat'){
                $loan->installments = ($request->amount_approved / $request->tenor_approved) + ($interest/$request->tenor_approved);
                $loan->interest_paid = 0;
                $loan->balance = $request->amount_approved + $interest;

            }else if($loan->repayment_type == 'balloon'){
                $loan->installments =  $interest/$request->tenor_approved;
                $loan->interest_paid = 0;
                $loan->balance = $request->amount_approved + $interest;
            }
            $year_plan->year_interest = $year_plan->year_interest + $loan->interest_paid;

            $maturity_date = Carbon::parse(date(now()))->addMinutes($request->tenor_approved);

            $year_plan->save();
            $loan->maturity = $maturity_date;
            $loan->save();
            $account = StagetAccount::where('year_id', $year_plan->id)->first();
            $account->total_asset = $account->total_asset - $loan->amount_approved + $loan->interest_paid;
            $account->save();

            $user_account = Account::where('user_id', $loan->user_id)->first();

            $user_account->total_savings = $user_account->total_savings + $loan->commission_paid;
            $user_account->save();

            $notification = array(
                'message' => 'Loan approved successfully!'.$loan->id,
                'alert-type' => 'success'
            );

        }
            
        
        
        

        return redirect()->route('loan.approve_loan')->with($notification);

    }

    public function Repayment(){
        $loans = DB::table('loan_applications')
        ->join('users', function ($join) {
            $join->on('users.id', '=', 'loan_applications.user_id')
                ->where('loan_applications.application_status', 'open')
                ->where('loan_applications.loan_type', 'external')
                ->where(function ($query) {
                    $query->where('loan_applications.repayment_type', 'flat')
                        ->orWhere('loan_applications.repayment_type', 'flat upfront interest')
                        ->orWhere('loan_applications.repayment_type', 'balloon')
                        ->orWhere(function ($query) {
                            $query->where('loan_applications.repayment_type', 'balloon upfront interest')
                                ->where('loan_applications.maturity', '<=', Carbon::now());
                        });
                });
        }) 
        ->join('external_borrowers', function ($join) {
            $join->on('external_borrowers.loan_id', "loan_applications.id");
        })   
        ->join('year_plans', 'year_plans.id', '=', 'loan_applications.year_id')
        ->select('loan_applications.*', 'users.name', 'year_plans.year', 'external_borrowers.borrower_name')
        ->get();

        $xy = 1;

        foreach($loans as $loan){
            if(($loan->maturity <= Carbon::now()) && ($loan->repayment_type === 'balloon')){
                $loan->installments = $loan->balance;
            }
            session()->push('loan_details', $loan);
        }

        return view('admin.external_loan_repayment', compact('loans', 'xy'));
    }

    public function CompletePayment(){
        $commission = 0;
        $loans = session()->pull('loan_details', []); // Second argument is a default value  
        $removed_loans = session()->pull('removed_loans', []); 
        // fix me first
        foreach($removed_loans as $removed_loan){
            $loan_year_plan = YearPlan::where('id', $removed_loan->year_id);
            if($loan_year_plan->interest_type == 'monthly'){
                $loan_removed = LoanApplication::where('id', $removed_loan->id)->first();
                $loan_removed->tenor = $loan_removed->tenor + 1;
                $loan_removed->balance = $loan_removed->balance + $loan_removed->amount_approved * ($loan_year_plan->interest_rate/100);
                $loan_removed->save();
            }
        }
        
               
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        
        foreach ($loans as $loan) {
            $user_loan = LoanApplication::where('id', $loan->id)->first(); // Use "first()" instead of "find()" to retrieve a single instance
            $loan_year = YearPlan::where('id', $user_loan->year_id)->first();
            $user_loan->balance = $user_loan->balance - $loan->installments;
            if($user_loan->repayment_type === "flat" || $user_loan->repayment_type === "balloon") {
                $interest = $user_loan->amount_approved * ($loan_year->external_interest/100);
                $commission = ($loan_year->external_commission/100) * $interest;
                $user_loan->interest_paid = $user_loan->interest_paid + $interest - $commission;
                $user_loan->commission_paid = $user_loan->commission_paid + $commission;
                $year_plan->year_interest = $year_plan->year_interest + $user_loan->interest_paid;
                $year_plan->save();
            }
            
            $repayment = array(
                'loan_id' => $loan->id,
                'amount' => $loan->installments,
                'year_id' => $year_plan->id

            );
            DB::table('loan_repayments')->insert($repayment);

            $user_loan->save();

            $loan = LoanApplication::where('id', '=', $loan->id)->first();

            if($loan->balance <= 10){
                $loan->application_status = 'close';
                $loan->save();
            }

            $account = StagetAccount::where('year_id', $year_plan->id)->first();
            $account->total_asset = $account->total_asset + $loan->installments;
            $account->save();

            $user_account = Account::where('user_id', $loan->user_id)->first();

            $user_account->total_savings = $user_account->total_savings + $commission;
            $user_account->save();

            
        } 
        session()->forget('loan_details');      

        $notification = array(
            'message' => 'Monthly loan repayment successful',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.dashboard')->with($notification);
    }
}
