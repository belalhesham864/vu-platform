<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteMemberRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\InviteMemberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\services\Team\inviteTeamServices;


class MangmentTeamController extends Controller implements HasMiddleware
{
public static function middleware(): array
{ 
    return[

    new Middleware('can:manage_team_members',except:['resetPassword']),
    ];
}
    public function index()
    {
        $users = User::with('roles')->where('company_id', auth()->user()->company_id)->get();
        return apiResponse(200, 'All Team', TeamResource::collection($users));
    }

    public function invite(InviteMemberRequest $request,inviteTeamServices $invite )
    {
        $user=$invite->invite($request);
        if(!$user){

            return apiResponse(201, 'error please try again latter');
        }
        
            return apiResponse(201, 'Invitation sent successfully');
    }
    public function resetPassword(SetPasswordRequest $request)
    {
        $data = $request->validated();
         $status = Password::reset(
            [
                'email' => $data['email'],
                'token' => $data['token'],
                'password' => $data['password'],
                'password_confirmation' => $request->input('password_confirmation'),
            ],
                 function ($user, $password) {
                $user->update([
                    'password' => Hash::make($password),
                    'status' => 'active',
                    'email_verified_at'=>now()
                ]);
            }

            );
            if ($status !== Password::PASSWORD_RESET) {
return apiResponse(422, 'Invalid or expired token');
                   }
                   return apiResponse(200, 'Password Reset successfully');


    }
    public function update(UpdateMemberRequest $request,User $user){
        $data=$request->validated();
        if($user->hasRole('Owner')){
            return apiResponse(403,'You cannot modify the Owner role');
        }
        if($user->company_id != auth()->user()->company_id){
      return apiResponse(404, 'Member not found');
        }
     $user->syncRoles([$data['role']]);
             $user->update(['status' => $data['status']]);
        return apiResponse(200, 'Member updated successfully', new TeamResource($user->fresh()));

        

    }
    public function resendInvite($id){
                  $user=User::find($id);
                if(!$user){
      return apiResponse(404, 'Member not found');
      }
          if($user->hasRole('Owner')){
            return apiResponse(403,'You cannot sent invite to owner');
        }
    
           if ($user->status !== 'invited') {
            return apiResponse(422, 'This member already has an active account');
        }
        DB::table('password_reset_tokens')->where('email',$user->email)->delete();
           $token = Password::createToken($user);

            $user->notify(new InviteMemberNotification($token));
                    return apiResponse(200, 'Invitation resent successfully');

    }
    public function delete($id){
              $user=User::find($id);
                if(!$user){
      return apiResponse(404, 'Member not found');
      }
                  if($user->hasRole('Owner')){
            return apiResponse(403,'You cannot delete the Owner');
        }
  
      $user->delete();
      return apiResponse(200, 'User Deleted Successfuly');

    }
}
