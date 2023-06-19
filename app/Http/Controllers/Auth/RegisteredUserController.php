<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Department;
use App\Models\State;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request)
    {
        $states = State::get();

        $user = $request->session()->get('user');
        // check user role and redirect to appropriate view
        if(Auth::user()->role === 'admin'){
            return view('admin.register_user_one', compact('user', 'states'));
        }
        
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   
         

    

    public function CreateOne(Request $request): RedirectResponse
    {

        $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'nationality' => ['required', 'string'],
            'state_of_origin' => ['required', 'integer'],
            'local_government_of_origin' => ['required', 'integer']            
        ]);

        if(empty($request->session()->get('user'))){
            $user = new User();
            $user->fill($validateData);
            $user->password = Hash::make($request->password);
            $request->session()->put('user', $user);

        }else{
            $user = $request->session()->get('user');
            $user->fill($validateData);
            $request->session()->put('user', $user);

        }

        return redirect()->route('admin.register.two');

    }
   

    
    public function CreatePageTwo(Request $request){

        $departments = Department::get();
        $branches = Branch::get();
        $user = $request->session()->get('user');
        $branch = new Branch();
        // check user role and redirect to appropriate view
        if(Auth::user()->role === 'admin'){
            return view('admin.register_user_two', compact('user', 'departments', 'branches', 'branch'));
        }


    }    

    public function CreateTwo(Request $request)
    {
        $requestData = $request->validate([            
            'nok_name' => ['required', 'string'],
            'nok_address' => ['required', 'string'],
            'nok_email' => ['required', 'string'],
            'password' => ['required'],
            'nok_phone' => ['required', 'string'],
            'nok_relationship' => ['required', 'string'],
            'branch_id' => ['required', 'integer'],
            'department_id' => ['required', 'integer'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $user = $request->session()->get('user');
        $user->fill($requestData);
        $user->password = Hash::make($request->password);

         $request->session()->put('user', $user);       

        return redirect()->route('admin.register.three');

       

    }

     public function CreatePageThree(Request $request){
        $user = $request->session()->get('user');
        if(Auth::user()->role === 'admin'){
             return view('admin.register_user_three', compact('user'));
        }       

    }


    public function CreateThree(Request $request): RedirectResponse
    {
        

        if($request->file('photo')){
            $file = $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/user_images/passports'), $filename);

            
        }

        if($request->file('signature')){
            $file = $request->file('signature');
            $filename_signature = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/user_images/signatures'), $filename_signature);
            
        }
        
        $user = $request->session()->get('user');
        $user->photo = $filename;
        $user->signature = $filename_signature;
        $user->save();
        $account = new Account();
        $account->user_id = $user->id;
        $account->total_savings = 0.00;
        $account->monthly_savings = $request->deposit;

        $account->save();
        event(new Registered($user)); 
        $request->session()->forget('user');
        
        $notification = array(
            'message' => "New user added successfully",
            'alert-type' => 'success'
        ); 
        
         if(Auth::user()->role === 'admin'){
            return redirect()->route('admin.users')->with($notification);
         }
         
         return back();
       

    }

    public function UpdateUser(Request $request){
        $user = User::where('id', $request->id);
         $validateData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string']                   
        ]);

        $user->fill($validateData);

        if($request->file('photo')){
            $file = $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/user_images/passports'), $filename);            
        }

        if($request->file('signature')){
            $file = $request->file('signature');
            $filename_signature = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads/user_images/signatures'), $filename_signature);
            
        }

        $user->photo = $filename;
        $user->signature = $filename_signature;

        $account = Account::where('user_id', $request->id);
        $account->monthly_savings = $request->monthly_savings;

        if($user->save() && $account->save()){
             $notification = array(
                'message' => "New user added successfully",
                'alert-type' => 'success'
            );
            return view('admin.dashboard')->with($notification);
        }

    }


}
