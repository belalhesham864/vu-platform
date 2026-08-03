<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Owner',
            'Admin',
            'HR',
            'HR-Interviewer',
            'Tech-Interviewer',
            'Account-Manager',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name'       => $role,
                'guard_name' => 'api',
            ]);
        }

        $permissions = [
            'manage_company_settings',
            'manage_team_members',
            'create_job',
            'edit_job',
            'delete_job',
            'view_candidates',
            'manage_candidates',
            'schedule_interview',
            'conduct_interview',
            'submit_feedback',
            'view_analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'api',
            ]);
        }

        Role::findByName('Owner', 'api')->givePermissionTo(Permission::all());
        Role::findByName('Admin', 'api')->givePermissionTo([
            'manage_company_settings',
            'manage_team_members',
            'create_job',
            'edit_job',
            'delete_job',
            'view_candidates',
            'manage_candidates',
            'view_analytics',
        ]);
        Role::findByName('Account-Manager', 'api')->givePermissionTo([
            'manage_company_settings',
            'manage_team_members',
            'view_analytics',
        ]);

        Role::findByName('HR', 'api')->givePermissionTo([
            'create_job',
            'edit_job',
            'view_candidates',
            'manage_candidates',
            'schedule_interview',
        ]);

        Role::findByName('HR-Interviewer', 'api')->givePermissionTo([
            'view_candidates',
            'conduct_interview',
            'submit_feedback',
        ]);

        Role::findByName('Tech-Interviewer', 'api')->givePermissionTo([
            'view_candidates',
            'conduct_interview',
            'submit_feedback',
        ]);
    }
}
