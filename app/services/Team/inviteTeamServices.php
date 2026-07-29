<?php

namespace App\services\Team;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\InviteMemberNotification;
use Illuminate\Support\Facades\DB;

class inviteTeamServices
{
    public function valdiation($request)
    {
        $validtor = Validator::make($request->all(), $request->rules());
        if ($validtor->fails()) {
            throw new Exception($validtor->errors());
        }
        return $validtor->validated();
    }
    public function store($data)
    {
        $user = User::create([
            'company_id' => auth()->user()->company_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(20)),
            'status' => 'invited',
        ]);
        return $user;
    }
    public function asginRole($user, $role)
    {
        $user->assignRole($role);
    }
    public function createToken($user)
    {

        $token = Password::createToken($user);
        return $token;
    }
    public function sendNotifay($user, $token)
    {

        $user->notify(new InviteMemberNotification($token));
    }
    public function invite($request)
    {
        DB::beginTransaction();

        try {
            $data = $this->valdiation($request);
            $user = $this->store($data);
            $this->asginRole($user, $data['role']);
            $token = $this->createToken($user);
            $this->sendNotifay($user, $token);
            DB::commit();

            return $user;
        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }
}
