<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Override;

class AdminDashboardController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
       
    }

    public function index(){
       $company=auth()->user()->company;
       $activeMember=User::where('company_id',$company->id)->where('status','active')->count();
       $pendingInvite=User::where('company_id',$company->id)->where('status','invited')->count();

          return apiResponse(200,'Admin Dashboard',[
         'active_team_members' => $activeMember,
            'pending_invites'  => $pendingInvite,
            'attention'=>$this->getAttention($company)

          ]);
    }
    private function getAttention($company){
        $items=[];
               $staleInvites=User::where('company_id',$company->id)->where('status','invited')
               ->where('created_at','<=',now()->subDays(3))->get(['name','created_at']);
               foreach($staleInvites as $invite){
                $day=$invite->created_at->diffInDays(now());
            $items[] = [
                'type' => 'pending_invite',
                'message' => "Sent {$day} days ago",
                'detail' => "{$invite->name} invite is still pending",
            ];
               }

    }
}
