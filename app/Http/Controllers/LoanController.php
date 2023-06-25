<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\LoanApplication;
use App\Models\StagetAccount;
use App\Models\User;
use App\Models\YearPlan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class LoanController extends Controller
{
    public function LoanApplication(){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $account = Account::where('user_id', '=', Auth::user()->id)->first();
        $loan = LoanApplication::where('user_id', Auth::user()->id)->where('application_status', 'pending')->orWhere('application_status', 'open')->get(); 
        if($loan->count()>=1){
            $notification = array(
                'message' => 'You have an openned loan',
                'alert-type' => 'error'
            );

            return back()->with($notification);
            
        }else if($year_plan === null){
            $notification = array(
                'message' => 'There is no active year plan!',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }
        if(Auth::user()->role == 'admin'){
            return view('admin.loan_application', compact('year_plan', 'account'));
        }else if(Auth::user()->role == 'member'){
            return view('member.loan_application', compact('year_plan', 'account'));
        }
        

    }

    public function ApplyLoan(Request $request){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $account = Account::where('user_id', '=', Auth::user()->id)->first();
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

        ;
        if($loanApplication->save()){
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

        if(Auth::user()->role == 'admin'){
           return redirect()->route('admin.dashboard')->with($notification);
        }else if(Auth::user()->role == 'member'){
           return redirect()->route('member.dashboard')->with($notification);
        }
        
        
        
    }
    public function ApproveLoan(){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $loans = DB::table('loan_applications')
            ->join('users', function(JoinClause $joinClause){
                $joinClause->on('users.id', '=', 'loan_applications.user_id')
                    ->where('loan_applications.application_status', '=', 'pending')
                    ->where('loan_applications.loan_type', 'internal');
            })
            ->select('loan_applications.*', 'users.name')
            ->get();
        if($year_plan === null){
             $notification = array(
                'message' => 'There is no active year plan!',
                'alert-type' => 'error'
            );

            return back()->with($notification);

        }
        $xy = 1;
        return view('admin.approve_loan', compact('loans', 'xy'));
    }
    public function ApproveLoanApplication(Request $request){

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
                $interest = $request->amount_approved * ($year_plan->interest_rate/100) * $request->tenor_approved;
            }else if($interest_type === 'one_off'){
                $interest = $request->amount_approved * ($year_plan->interest_rate/100);
            }

            if($loan->repayment_type == 'balloon upfront interest'){                
                $loan->installments = $request->amount_approved;
                $loan->balance = $request->amount_approved;
                $loan->interest_paid = $interest;
            }else if($loan->repayment_type == 'flat upfront interest'){
                $loan->installments = $request->amount_approved / $request->tenor_approved;
                $loan->balance = $request->amount_approved;
                $loan->interest_paid = $interest;
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
            $account->total_asset = $account->total_asset - $loan->amount_approved;
            $account->save();

            $notification = array(
                'message' => 'Loan approved successfully!'.$loan->id,
                'alert-type' => 'success'
            );

        }
            
        
        
        

        return redirect()->route('loan.approve_loan')->with($notification);
        
    }

    public function OpenLoanRepayment(){
        $loans = DB::table('loan_applications')
        ->join('users', function ($join) {
            $join->on('users.id', '=', 'loan_applications.user_id')
                ->where('loan_applications.application_status', 'open')
                ->where('loan_applications.loan_type', 'internal')
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
        ->join('year_plans', 'year_plans.id', '=', 'loan_applications.year_id')
        ->select('loan_applications.*', 'users.name', 'year_plans.year')
        ->get();

        $xy = 1;

        foreach($loans as $loan){
            if(($loan->maturity <= Carbon::now()) && ($loan->repayment_type === 'balloon')){
                $loan->installments = $loan->balance;
            }
            session()->push('loan_details', $loan);
        }

        return view('admin.repay_loan', compact('loans', 'xy'));
    }

    public function RemoveLoan(Request $request){
        $loans = session()->pull('loan_details', []); // Second argument is a default value  
        $removed_loans = session()->pull('removed_loans', []);
        $loanRemoved = LoanApplication::where('id', $request->id)->first();

        array_push($removed_loans, $loanRemoved);
        unset($loans[$request->loan]);

        
        session()->put('removed_loans', $removed_loans);      
        session()->put('loan_details', $loans);
        $loans = session()->pull('loan_details', []);
        $removed_loans = session()->pull('removed_loans', []);
        $users = User::all();
        $xy = 1;
        
        return view('admin.repay_loan', compact('loans', 'xy', 'removed_loans'));
    }

    public function CompletePayment(){
        $loans = session()->pull('loan_details', []);   
                       
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        foreach ($loans as $loan) {
            $user_loan = LoanApplication::where('id', '=', $loan->id)->first(); 
             $loan_year = YearPlan::where('id', $user_loan->year_id)->first();

             if($user_loan->balance <= 10){
                $user_loan->balance = $user_loan->balance - $loan->installments;
             }else{
                $user_loan->balance = 0;
             }
            
            
            
            if($user_loan->repayment_type === "flat" || $user_loan->repayment_type === "balloon") {
                $interest = $user_loan->amount_approved * ($loan_year->interest_rate/100);
                $user_loan->interest_paid = $user_loan->interest_paid + $interest;
                $year_plan->year_interest = $year_plan->year_interest + $interest;
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

            if($loan->balance <= 0){
                $loan->application_status = 'close';
                $loan->save();
            }

            $account = StagetAccount::where('year_id', $year_plan->id)->first();
            $account->total_asset = $account->total_asset + $loan->installments;
            $account->save();

            
        } 

        $removed_loans = session()->pull('removed_loans', []); 
        // fix me first
        foreach($removed_loans as $removed_loan){
            $loan_year_plan = YearPlan::where('id', $removed_loan->year_id)->first();
            if($loan_year_plan->interest_type == 'monthly'){
                $loan_removed = LoanApplication::where('id', $removed_loan->id)->first();
                $loan_removed->tenor = $loan_removed->tenor + 1;
                $loan_removed->balance = $loan_removed->balance + $loan_removed->amount_approved * ($loan_year_plan->interest_rate/100);
                $loan_removed->save();
            }
        }
        
        session()->forget('loan_details');      

        $notification = array(
            'message' => 'Monthly loan repayment successful',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.dashboard')->with($notification);
    }
}
