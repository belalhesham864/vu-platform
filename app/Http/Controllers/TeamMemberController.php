<?php

namespace App\Http\Controllers;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $member = TeamMember::create($validated);

        return apiResponse(201, 'Team member created successfully', new TeamMemberResource($member));
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamMember $teamMember)
    {
        return apiResponse(200, 'Success', new TeamMemberResource($teamMember->load('user')));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamMember $teamMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validated();

        $teamMember->update($validated);

        return apiResponse(200, 'Team member updated successfully', new TeamMemberResource($teamMember));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();

        return apiResponse(200, 'Team member deleted successfully');
    }







    public function invite(Request $request)
    // {
    //     $data = $request->validated();

    //     $role = Role::where('name', $data['role'])->firstOrFail();
    //     $data['role'] = $role;

    //     $user = User::where('name', $data['user_name'])->where('email', $data['user_email'])->firstOrFail();

    //     return apiResponse(200, 'Invitation sent successfully', new TeamMemberResource($user));
    // }
    {
        $request->validate([
            'user_name' => 'required|exists:users,name',
            'user_email' => 'required|exists:users,email',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::where('name', $request->user_name)
            ->where('email', $request->user_email)
            ->firstOrFail();
        // $user = User::findOrFail($request->user_id);

        Role::findByName($request->role);

        $invitation = Invitation::create([
            'company_id' => Auth::user()->company->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'role' => $request->role,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        $user->notify(new TeamInvitationNotification($invitation));

        return apiResponse(
            200,
            'Invitation sent successfully',
            new TeamMemberResource($user)
        );
    }
}
