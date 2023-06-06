<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function AgentDashboard(){
        $users = User::all();
        return view('agent.agent_dashboard', ['users' => $users]);
    }
}
