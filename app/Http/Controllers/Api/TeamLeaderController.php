<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class TeamLeaderController extends Controller
{
    
    public function show(){
        $teamleader = User::where('type','Team Leader')->get();
        return response([ 'status' => true, 'list' => $teamleader ], 200);
    }
}
