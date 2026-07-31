<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(){
            $plans = Plan::with('features')->where('is_active', true)->get();
    return apiResponse(200, 'All Plans ', $plans);
    }
    public function show(Plan $plan){
$plan->load('features');
    return apiResponse(200, 'Plan details', $plan);

    }
}
