<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\MemberRequest;
use App\Http\Requests\TeamMember\TeamMemberRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\TeamMember\TeamMemberResource;
use App\Models\Invitation;
use App\Models\TeamMember;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $members = User::where('company_id', Auth::user()->company_id)
            ->get();

        return apiResponse(200, 'Success', TeamMemberResource::collection($members));
    }
}
