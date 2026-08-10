<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Category;
use App\Models\Position;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class OwnerDashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('role:Owner'),
        ];
    }

    public function index()
    {
        $company = auth()->user()->company;
        $companyId = $company ? $company->id : null;

        if (!$companyId) {
            return apiResponse(400, 'User company not found');
        }

        $companyPositionIds = Position::where('company_id', $companyId)->pluck('id');

        $candidatesProcessed = Application::whereIn('position_id', $companyPositionIds)->count();

        $hiredApplications = Application::whereIn('position_id', $companyPositionIds)
            ->where(function ($query) {
                $query->whereIn('decision', ['accepted', 'hired', 'Approved', 'Accepted'])
                    ->orWhereIn('status', ['Accepted', 'accepted']);
            })
            ->get();

        $avgTimeToHireDays = $this->calculateAvgDays($hiredApplications);

        $costSavedAmount = $candidatesProcessed * 35 + $hiredApplications->count() * 1200;

        $performanceByDepartment = [];

        foreach (Category::all() as $category) {
            $catPositionIds = Position::where('company_id', $companyId)
                ->where('category_id', $category->id)
                ->pluck('id');

            if ($catPositionIds->isEmpty()) {
                continue;
            }

            $catHiredApps = Application::whereIn('position_id', $catPositionIds)
                ->where(function ($query) {
                    $query->whereIn('decision', ['accepted', 'hired', 'Approved', 'Accepted'])
                        ->orWhereIn('status', ['Accepted', 'accepted']);
                })
                ->get();

            $performanceByDepartment[] = [
                'department' => $category->name,
                'hires'      => $catHiredApps->count(),
                'avg_days'   => $this->calculateAvgDays($catHiredApps),
            ];
        }

        return apiResponse(200, 'Owner Dashboard', [
            'time_to_hire'            => $avgTimeToHireDays,
            'cost_saved_this_quarter' => $costSavedAmount,
            'candidates_processed'    => $candidatesProcessed,
            'recruitment_performance_by_department' => $performanceByDepartment,
        ]);
    }

    private function calculateAvgDays($applications): int
    {
        $count = $applications->count();

        if ($count === 0) {
            return 0;
        }

        $totalDays = $applications->sum(function ($app) {
            $created = $app->created_at;
            $decisionDate = $app->decision_date ?? $app->updated_at;

            return ($created && $decisionDate)
                ? max(1, (int) $created->diffInDays($decisionDate))
                : 0;
        });

        return (int) round($totalDays / $count);
    }
}
