<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Deposit;
use App\Models\LoanApplication;
use App\Models\StagetAccount;
use App\Models\YearPlan;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function MemberDashboard(){
        $year_plan = YearPlan::latest()->get(); 
         $users = User::where('status', 'active')->count();
        
        if(count($year_plan) > 0 ){
            $total_savings = StagetAccount::where('year_id', $year_plan[0]->id)->first();
            $year_savings = DB::table('deposits')->where('year_id', $year_plan[0]->id)->sum('amount');
            $ind_year_savings = DB::table('deposits')->where('year_id', $year_plan[0]->id)->where('user_id', Auth::user()->id)->sum('amount');
            $total_liability = DB::table('liabilities')->where('year_id', $year_plan[0]->id)->sum('cost');
            
            $ind_total_asset = Account::where('user_id', Auth::user()->id)->first();
           

            $total_asset = $total_savings->total_asset;
            $stake = 0.00;
            $total_stake = 0.00;

            if ($total_asset != 0) {
                $total_stake = 100 / $total_asset; //fix me soon or else
            } else {
                // Handle the case where $total_asset is zero to avoid division by zero error
            }
            if($year_savings != 0){
                $stake = 100/$year_savings * $ind_year_savings;
            }
            $year_interest = $stake/100 * $year_plan[0]->year_interest;
            
            $total_ind_asset = $ind_total_asset->total_savings ?? 0.00;
            
        }else{
            $total_savings = 0;
            $year_savings = 0;
            $ind_year_savings= 0;
            $total_liability = 0;
            $ind_total_asset = 0;
            $total_asset = 0;
            $total_ind_asset= 0;
            $stake = 0;
            $total_stake = 0;
            $year_plan = new YearPlan();
            $year_plan->year = 2023;
            $year_interest = 0;

        }

         

        

        $loans = LoanApplication::join('users', 'users.id', '=', 'loan_applications.user_id')
            ->where('user_id', Auth::user()->id)
            ->get(['loan_applications.*', 'users.name']);

       // Create an empty array to store the dates
        $years = [];

        // Start with the current month
        $month = Carbon::now();

        // Loop for the past 12 months
        for ($i = 0; $i < 12; $i++) {
            // Add the first day of the month to the array
            $years[] = $month->startOfMonth()->format('m/d/Y');
            
            // Move to the previous month
            $month->subMonth();
        }

        // Reverse the array to get the dates in descending order
        $years = array_reverse($years);

        $deposits = [];

        foreach($years as $yr){
            $year = Carbon::parse($yr)->format('Y'); // Year for filtering
            $month = Carbon::parse($yr)->format('m'); // Month for filtering

            $data = Deposit::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('user_id', Auth::user()->id)
                ->sum('amount');

            array_push($deposits, $data);

        }

        
        
        $dashboard_info = array(
            'total_savings' => $total_savings,
            'year_plan' => $year_plan,
            'year_savings' => $year_savings,
            'ind_total_asset' => $total_ind_asset,
            'ind_year_savings' => $ind_year_savings,
            'stake' => $stake,
            'total_liability' => $total_liability,
            'loans' => $loans,
            'years' => $years,
            'deposits' => $deposits,
            'users' => $users,
            'total_stake' => $total_stake,
            'year_interest' => $year_interest
        );
        return view('member.index', compact('dashboard_info'));
    }
    
}
