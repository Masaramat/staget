<?php

namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\Deposit;
use App\Models\LoanApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\YearPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function AdminDashboard(){
        $year_plan = YearPlan::latest()->get();        

        $total_savings = Account::join('users', 'users.id', '=', 'accounts.user_id')
            ->where('users.status', 'active')
            ->sum('accounts.total_savings');
        $year_savings = DB::table('deposits')->where('year_id', $year_plan[0]->id)->sum('amount');
        $ind_year_savings = DB::table('deposits')->where('year_id', $year_plan[0]->id)->where('user_id', Auth::user()->id)->sum('amount');
        $total_liability = DB::table('liabilities')->where('year_id', $year_plan[0]->id)->sum('cost');

        
        $ind_total_asset = Account::where('user_id', Auth::user()->id)->first();
        $users = User::where('status', 'active')->get();

        $stake = 100/$total_savings * $ind_total_asset->total_savings;

        $loans = LoanApplication::join('users', 'users.id', '=', 'loan_applications.user_id')
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
                ->sum('amount');

            array_push($deposits, $data);

        }

        
        
        $dashboard_info = array(
            'total_savings' => $total_savings,
            'year_plan' => $year_plan,
            'year_savings' => $year_savings,
            'ind_total_asset' => $ind_total_asset->total_savings,
            'ind_year_savings' => $ind_year_savings,
            'stake' => $stake,
            'total_liability' => $total_liability,
            'loans' => $loans,
            'years' => $years,
            'deposits' => $deposits
        );
        return view('admin.index', compact('dashboard_info'));
    }

     public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
    public function AdminLogin(){
        return view('admin.admin_login');
    }

   
    public function AdminProfile(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view', compact('profileData'));

    }

    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('uploads/admin_images'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/admin_images'), $filename);

            $data['photo'] = $filename;
        }
        $data->save();

        $notification = array(
            'message' => 'Admin profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    

    public function GetUsers(){
        
        $users = User::all();
        $xy = 1;

        return view('admin.users_view', compact('users', 'xy'));

    }
    public function ViewUser(Request $request){
        $profileData = User::find($request->id);
        return view('admin.users.user_profile_view', compact('profileData'));
    }

    public function AdminChangePassword(){
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password', compact('profileData'));
    }

    public function AdminUpdatePassword(Request $request){
        $request->validate([
            'old_password'=>'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            $notification = array(
                'message' => 'Old Password Incorrect',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }

        // update password
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password changed successfully!',
            'alert-type' => 'success'
        );

        return back()->with($notification);

    }

    //configurations functions
    public function NewYearPlan(){
        $year_plan = YearPlan::where('status', '=', 'open')->first();
        if(!empty($year_plan)){
            $notification = array(
                'message' => "Close openned year first!",
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
        return view('admin.config.admin_open_year');        
    }
    public function OpenYear(Request $request){
        $year = new YearPlan();
        $year->year = Date('Y');
        $year->min_savings = $request->min_savings;
        $year->loan_percentage = $request->loan_percentage;
        $year->interest_rate = $request->interest_rate;
        $year->interest_type = $request->interest_type;

        $year->save();

        $notification = array(
            'message' => "Year openned successfully",
            'alert-type' => 'success'
        );

        return redirect()->route('admin.dashboard')->with($notification);
        
    }

    public function Liability(){
        $year_plan = YearPlan::where('status', 'open')->first();
        if($year_plan === null){
            $notification = array(
                'message' => 'Year is not opened. You need to add liablility to oppened year!',
                'alert-type' => 'error'
            );

            return back()->with($notification);
        }else{
            $xy = 1;
            $liabilities = DB::table('liabilities')->where('year_id', $year_plan->id)->get();
            $total_liabilities = DB::table('liabilities')->where('year_id', $year_plan->id)->select('sum(cost)');
            
            return view('admin.config.admin_liabilities', compact('year_plan', 'liabilities', 'total_liabilities', 'xy'));
        }
    }

    public function SaveLiability(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'cost' => 'required|numeric',
            'year_id' => 'required'
        ]);

        DB::table('liabilities')->insert($data);

         $notification = array(
                'message' => 'Successfull!',
                'alert-type' => 'success'
            );

            return back()->with($notification);

    }

    public function CloseYearView(){
        $year_plan = YearPlan::where('status', 'open')->first();

        if($year_plan === null){
            $notification = array(
                'message' => 'Year is not opened!',
                'alert-type' => 'error'
            );

            return back()->with($notification);

        }else{
            $year_savings = DB::table('deposits')->where('year_id', $year_plan->id)->sum('amount');
            $total_savings = DB::table('accounts')->sum('total_savings');
            $total_liability = DB::table('liabilities')->where('year_id', $year_plan->id)->sum('cost');           

            $year_info = array(
                'year_savings' => $year_savings,
                'total_savings' => $total_savings,
                'total_liability' => $total_liability,
                'year_interest' => $year_plan->year_interest,
                'year' => $year_plan->year
            );

            return view('admin.config.admin_close_year', compact('year_info'));
        }
    }

    public function CloseYear(){
        $year_plan = YearPlan::where('status', 'open')->first();
        $year_savings = DB::table('deposits')->where('year_id', $year_plan->id)->sum('amount');
        $total_liability = DB::table('liabilities')->where('year_id', $year_plan->id)->sum('cost');

        $accounts = Account::join('users', 'users.id', '=', 'accounts.user_id')
            ->where('users.status', 'active')
            ->get(['accounts.*']);
        $ind_liability = $total_liability/$accounts->count();

        foreach($accounts as $account){
            $total_individual_savings = Deposit::where('user_id', $account->user_id)->where('year_id', $year_plan->id)->sum('amount');
            $per_savings = (100/$year_savings) * $total_individual_savings;
            $year_int = ($per_savings/100) * $year_plan->year_interest;

            $account->total_savings = $account->total_savings + $year_int - $ind_liability;

            $account->save();
            $interest = array(
                'user_id' => $account->user_id,
                'year_id' => $year_plan->id,
                'amount' => $year_int
            );

            DB::table('interests')->insert($interest);

            $year_plan->status = 'close';

            $year_plan->save();
        }

        $notification = array(
            'message' => 'Year is not closed successfully!',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.dashboard')->with($notification);


    }
}
