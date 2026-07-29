<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\subscribers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function state(){
        $totelCandidatesHired=Application::where('status','Accepted')->count();
        $totelApplication=Application::count();
        $successRate= $totelApplication>0 ? round(($totelCandidatesHired/$totelApplication)*100,1):0;
        $activeCompanies=Company::where('status','active')->count();
        return apiResponse(200,'Home Page',[
      'total_candidates_hired' => $totelCandidatesHired,
        'success_rate'           => $successRate,
        'active_companies'       => $activeCompanies,
        ]);
    }
    public function subscriber(Request $request){
          $data= $request->validate(['email'=>'required|email|max:70']);
        $sub=subscribers::create(['email'=>$data['email']]);
        if(!$sub){
            return apiResponse(400,'please Try again Latter');
            }
            return apiResponse(201,'Subscriber success');
    }
}
