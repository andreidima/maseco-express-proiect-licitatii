<?php

namespace App\Http\Controllers\Concerns;

use App\Models\AppNotification;
use App\Models\CronJobLog;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use App\Models\Ltm\Lot;
use App\Models\SupportThread;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait HasDashboardStatistics
{
    protected function gatherDashboardStatistics(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('activ', true)->count();
        $inactiveUsers = max($totalUsers - $activeUsers, 0);

        $roleDistribution = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->get();

        $activationDistribution = User::select(DB::raw('activ as label'), DB::raw('COUNT(*) as total'))
            ->groupBy('activ')
            ->get()
            ->map(function ($row) {
                $row->label = $row->label ? 'active' : 'inactive';
                return $row;
            });

        $notificationAudience = AppNotification::select('audience', DB::raw('COUNT(*) as total'))
            ->groupBy('audience')
            ->get();

        $notificationTypes = AppNotification::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $supportStatus = SupportThread::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $supportSeverity = SupportThread::select('problem_severity', DB::raw('COUNT(*) as total'))
            ->groupBy('problem_severity')
            ->get();

        $totalSupportThreads = SupportThread::count();

        $cronStatus = CronJobLog::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $cronJobs = CronJobLog::select('job_name', DB::raw('COUNT(*) as total'))
            ->groupBy('job_name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $ltmKpis = [
            'totalAuctions' => Auction::count(),
            'openAuctions' => Auction::where('status', 'deschisă')->orWhere('status', 'deschisŽŸ')->count(),
            'awardedAuctions' => Auction::where('status', 'atribuită')->orWhere('status', 'atribuitŽŸ')->count(),
            'totalLots' => Lot::count(),
            'activeClients' => Client::has('contracts')->count(),
            'activeCarriers' => Carrier::has('contracts')->count(),
        ];

        $ltmTopClients = Contract::select('client_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('client_id')
            ->groupBy('client_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['client', 'currency'])
            ->limit(6)
            ->get();

        $ltmTopCarriers = Contract::select('carrier_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('carrier_id')
            ->groupBy('carrier_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['carrier', 'currency'])
            ->limit(6)
            ->get();

        $ltmTopAuctions = Auction::with(['client', 'currency'])
            ->orderByDesc('estimated_value_eur')
            ->limit(10)
            ->get();

        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'currenciesCount' => Currency::count(),
            'userRoleDistribution' => $roleDistribution,
            'userActivationDistribution' => $activationDistribution,
            'notificationAudienceDistribution' => $notificationAudience,
            'notificationTypeDistribution' => $notificationTypes,
            'supportStatusDistribution' => $supportStatus,
            'supportSeverityDistribution' => $supportSeverity,
            'supportThreadsCount' => $totalSupportThreads,
            'cronStatusDistribution' => $cronStatus,
            'cronJobDistribution' => $cronJobs,
            'ltmKpis' => $ltmKpis,
            'ltmTopClients' => $ltmTopClients,
            'ltmTopCarriers' => $ltmTopCarriers,
            'ltmTopAuctions' => $ltmTopAuctions,
        ];
    }
}
