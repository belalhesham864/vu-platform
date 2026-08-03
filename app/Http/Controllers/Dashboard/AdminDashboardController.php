<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\subscriptions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminDashboardController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:api'),
            new Middleware('role:Admin'),
        ];
    }

    public function index()
    {
        $company = auth()->user()->company;

        $activeMember = User::where('company_id', $company->id)
            ->where('status', 'active')
            ->count();

        $pendingInvite = User::where('company_id', $company->id)
            ->where('status', 'invited')
            ->count();

        $activeSubscription = subscriptions::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderByDesc('end_at')
            ->first();

        if ($activeSubscription && $activeSubscription->end_at) {
            $daysLeft = now()->diffInDays($activeSubscription->end_at, false);
            $planRenewsIn = max(0, (int) $daysLeft);
        }

        return apiResponse(200, 'Admin Dashboard', [
            'active_team_members' => $activeMember,
            'pending_invites'     => $pendingInvite,
            'plan_renews_in'      => $planRenewsIn ?? null,
            'attention'           => $this->getAttention($company, $activeSubscription),
        ]);
    }

    private function getAttention($company, $activeSubscription)
    {
        $items = [];

        $staleInvites = User::where('company_id', $company->id)
            ->where('status', 'invited')
            ->where('created_at', '<=', now()->subDays(3))
            ->get(['name', 'created_at']);

        foreach ($staleInvites as $invite) {
            $day = $invite->created_at->diffInDays(now());
            $items[] = [
                'type'    => 'pending_invite',
                'message' => "Sent {$day} days ago",
                'detail'  => "{$invite->name}'s invite is still pending",
            ];
        }

        if ($activeSubscription && $activeSubscription->end_at) {
            $endAt = Carbon::parse($activeSubscription->end_at);
            $daysLeft = now()->diffInDays($endAt, false);

            if ($daysLeft >= 0 && $daysLeft <= 30) {
                $items[] = [
                    'type'    => 'billing_expiry',
                    'message' => 'Update before renewal',
                    'detail'  => 'Billing method expires this month',
                ];
            }
        }

        return $items;
    }
}
