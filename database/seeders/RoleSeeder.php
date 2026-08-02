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
            'HR Interviewer',
            'Technical Interviewer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'api',
            ]);
        }
        $permissions = [
            'manage_company_settings',
            'manage_team_members',
            'view_jobs',
            'create_job',
            'edit_job',
            'delete_job',
            'view_candidates',
            'manage_candidates',
            'schedule_interview',
            'conduct_interview',
            'submit_feedback',
            'view_analytics',
            'view_applications',
            'create_application',
            'edit_application',
            'delete_application',
            'view_categories',
            'create_category',
            'edit_category',
            'delete_category',
            'view_evaluations',
            'create_evaluation',
            'edit_evaluation',
            'delete_evaluation',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }
        Role::findByName('Owner', 'api')->syncPermissions(Permission::all());
        Role::findByName('Admin', 'api')->syncPermissions([
            'manage_company_settings',
            'manage_team_members',
            'create_job',
            'edit_job',
            'delete_job',
            'view_candidates',
            'manage_candidates',
            'view_analytics',
        ]);
        Role::findByName('HR', 'api')->syncPermissions([
            'create_job',
            'edit_job',
            'view_candidates',
            'manage_candidates',
            'schedule_interview',
        ]);
        Role::findByName('HR Interviewer', 'api')->syncPermissions([
            'view_candidates',
            'conduct_interview',
            'submit_feedback',
        ]);
        Role::findByName('Technical Interviewer', 'api')->syncPermissions([
            'view_candidates',
            'conduct_interview',
            'submit_feedback',
        ]);
    }
}
