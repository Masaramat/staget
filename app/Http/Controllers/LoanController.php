<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\LoanApplication;
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
        return view('admin.loan_application', compact('year_plan', 'account'));

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
        
        return redirect()->route('admin.dashboard')->with($notification);
        
    }
    public function ApproveLoan(){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $loans = DB::table('loan_applications')
            ->join('users', function(JoinClause $joinClause){
                $joinClause->on('users.id', '=', 'loan_applications.user_id')
                    ->where('loan_applications.application_status', '=', 'pending');
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
            $loan->balance = $request->amount_approved;
            $loan->application_status = 'open';
            $interest_type = $year_plan->interest_type;

            if($interest_type === 'monthly'){
                $interest = $request->amount_approved * ($year_plan->interest_rate/100) * $request->tenor_approved;
            }else if($interest_type === 'one_off'){
                $interest = $request->amount_approved * ($year_plan->interest_rate/100);
            }

            if($loan->repayment_type == 'balloon upfront interest'){                
                $loan->installments = $request->amount_approved;
                $loan->interest_paid = $interest;
            }else if($loan->repayment_type == 'flat upfront interest'){
                $loan->installments = $request->amount_approved / $request->tenor_approved;
                $loan->interest_paid = $interest;
            }else if($loan->repayment_type == 'flat'){
                $loan->installments = ($request->amount_approved / $request->tenor_approved) + ($interest/$request->tenor_approved);
                $loan->interest_paid = 0;

            }else if($loan->repayment_type == 'balloon'){
                $loan->installments =  $interest/$request->tenor_approved;
                $loan->interest_paid = 0;
            }
            $year_plan->year_interest = $year_plan->year_interest + $loan->interest_paid;

            $maturity_date = Carbon::parse(date(now()))->addMinutes($request->tenor_approved);

            $year_plan->save();
            $loan->maturity = $maturity_date;
            $loan->save();
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
                    ->where(function ($query) {
                        $query->where('loan_applications.repayment_type', 'flat')
                            ->orWhere(function ($query) {
                                $query->where('loan_applications.repayment_type', 'balloon')
                                    ->where('loan_applications.maturity', '<=', Carbon::now());
                            });
                    });
            })
            ->join('year_plans', 'year_plans.id', '=', 'loan_applications.year_id')
            ->select('loan_applications.*', 'users.name', 'year_plans.year')
            ->get();

        $xy = 1;

        foreach($loans as $loan){
            session()->push('loan_details', $loan);
        }

        return view('admin.repay_loan', compact('loans', 'xy'));
    }

    public function RemoveLoan(Request $request){
        $loans = session()->pull('loan_details', []); // Second argument is a default value       
        unset($loans[$request->loan]);
      
        session()->put('loan_details', $loans);
        $loans = session()->pull('loan_details', []);
        $users = User::all();
        $xy = 1;
        
        return view('admin.repay_loan', compact('loans', 'xy'));
    }

    public function CompletePayment(){
        $loans = session()->pull('loan_details', []); // Second argument is a default value       
        $year_plan = YearPlan::where('status', '=', 'open')->first(); 
               

        foreach ($loans as $loan) {
            $user_loan = LoanApplication::where('id', '=', $loan->id)->first(); // Use "first()" instead of "find()" to retrieve a single instance
            $user_loan->balance = $user_loan->balance - $loan->installments;            
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

            
        } 
        session()->forget('loan_details');      

        $notification = array(
            'message' => 'Monthly loan repayment successful',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.dashboard')->with($notification);
    }
}
