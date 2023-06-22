<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Deposit;
use App\Models\StagetAccount;
use App\Models\User;
use App\Models\YearPlan;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function Deposit()
    {
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        if($year_plan == null){
             $notification = array(
                'message' => 'You do not have an oppened year!',
                'alert-type' => 'error'
            );

        return back()->with($notification);
        }
        $xy = 1;

        $payers = DB::table('users')
            ->join('accounts', function(JoinClause $joinClause){
                $joinClause->on('users.id', '=', 'accounts.user_id')
                    ->where('users.status', '=', 'active');
            })
            ->select('users.id', 'users.name', 'accounts.total_savings', 'accounts.monthly_savings')
            ->get();
        $year_plan = YearPlan::where('status', '=', 'open')->first();

        foreach($payers as $payer){
            session()->push('payers_details', $payer);
        }

        
        return view('admin.users.admin_deposit', compact('xy', 'payers', 'year_plan'));

    }


    public function RemoveUserPayment(Request $request){
        $payers = session()->pull('payers_details', []); // Second argument is a default value
       
        unset($payers[$request->payer]);
      
        session()->put('payers_details', $payers);
        $payers = $request->session()->get('payers_details');                     
        

        $users = User::all();
        $xy = 1;
        
        return view('admin.users.admin_deposit', compact('users', 'payers', 'xy'));
    }
    public function CompletePayment(){
        $payers = session()->pull('payers_details', []);
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        $deposits = array();
        $sum = 0;

        foreach ($payers as $payer) {
            $user_account = Account::where('user_id', '=', $payer->id)->first(); // Use "first()" instead of "find()" to retrieve a single instance
            $deposit = array(
                'user_id' => $payer->id,
                'amount' => $payer->monthly_savings,
                'year_id' => $year_plan->id
            );
            $sum = $sum + $payer->monthly_savings;

            array_push($deposits, $deposit);
            
            if ($user_account) {
                $amount = $user_account->total_savings + $payer->monthly_savings;                
                $user_account->total_savings = $amount;
                $user_account->save();
            }
        }

        $acct = StagetAccount::where('year_id', $year_plan->id)->first();
        $acct->total_asset = $acct->total_asset + $sum;
        $acct->save();        

        Deposit::insert($deposits);

        $notification = array(
            'message' => 'Monthly deposits successful',
            'alert-type' => 'success'
        );

        return redirect('admin/users')->with($notification);

    }

    public function SingleDeposit(Request $request){

        $year_plan = YearPlan::where('status', '=', 'open')->first();
        if($year_plan == null){
             $notification = array(
                'message' => 'You do not have an oppened year!',
                'alert-type' => 'error'
            );

        return back()->with($notification);
        }
        $account = Account::where('user_id', $request->id)->first();
        $account->total_savings = $account->total_savings + $request->amount;

        $staget_account = StagetAccount::where('year_id', $year_plan->id)->first();
        $staget_account->total_asset = $staget_account->total_asset + $request->amount;

        $deposit = new Deposit();
        $deposit->user_id = $request->id;
        $deposit->amount = $request->amount;
        $deposit->year_id = $year_plan->id;

        if($account->save() && $staget_account->save() && $deposit->save()){
            $notification = array(
                'message' => 'Deposit successful!',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        }
    }

    
}
