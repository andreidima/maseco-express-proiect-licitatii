<?php

namespace App\Services\Insights;

use App\Models\AppNotification;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use App\Models\Ltm\Lot;
use App\Models\Ltm\Route as LtmRoute;
use App\Models\SupportThread;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InsightsService
{
    public function staffDashboard(): array
    {
        return array_merge(
            $this->userStats(),
            $this->notificationStats(),
            $this->supportStats(),
            $this->ltmStats(),
        );
    }

    public function participantDashboard(int $participantUserId, ?int $carrierId): array
    {
        $carrierBidQuery = Bid::query()->where('carrier_id', $carrierId);

        $myBidStatusDistribution = $carrierId
            ? (clone $carrierBidQuery)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->orderByDesc('total')
                ->get()
            : collect();

        $mySupportStatusDistribution = SupportThread::query()
            ->where('participant_user_id', $participantUserId)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();

        $openAuctions = Auction::query()
            ->where('status', 'deschisă')
            ->orWhere('status', 'deschisŽŸ')
            ->count();

        return [
            'participantOpenAuctions' => $openAuctions,
            'participantCarrierBidCount' => $carrierId ? (clone $carrierBidQuery)->count() : 0,
            'participantSupportThreadCount' => SupportThread::query()->where('participant_user_id', $participantUserId)->count(),
            'participantMyBidStatusDistribution' => $myBidStatusDistribution,
            'participantMySupportStatusDistribution' => $mySupportStatusDistribution,
        ];
    }

    public function reportAuctions(): array
    {
        $totalAuctions = Auction::count();
        $statusDistribution = $this->distribution(Auction::query(), 'status');
        $typeDistribution = $this->distribution(Auction::query(), 'type');

        $auctionsPerClient = Auction::query()
            ->whereNotNull('client_id')
            ->select('client_id', DB::raw('COUNT(*) as total'))
            ->groupBy('client_id')
            ->with('client')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $auctionsPerRoute = Auction::query()
            ->whereNotNull('route_id')
            ->select('route_id', DB::raw('COUNT(*) as total'))
            ->groupBy('route_id')
            ->with('route')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topAuctionsByEstimatedValue = Auction::query()
            ->with(['client', 'currency'])
            ->orderByDesc('estimated_value_eur')
            ->limit(10)
            ->get();

        $estimatedValueSummary = Auction::query()
            ->selectRaw('AVG(estimated_value_eur) as avg_value, SUM(estimated_value_eur) as sum_value, MAX(estimated_value_eur) as max_value')
            ->first();

        return [
            'totalAuctions' => $totalAuctions,
            'auctionStatusDistribution' => $statusDistribution,
            'auctionTypeDistribution' => $typeDistribution,
            'auctionsPerClient' => $auctionsPerClient,
            'auctionsPerRoute' => $auctionsPerRoute,
            'topAuctionsByEstimatedValue' => $topAuctionsByEstimatedValue,
            'auctionEstimatedValueSummary' => $estimatedValueSummary,
        ];
    }

    public function reportBids(): array
    {
        $totalBids = Bid::count();
        $statusDistribution = $this->distribution(Bid::query(), 'status');

        $bidsPerCarrier = Bid::query()
            ->whereNotNull('carrier_id')
            ->select('carrier_id', DB::raw('COUNT(*) as total'))
            ->groupBy('carrier_id')
            ->with('carrier')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $bidsPerAuction = Bid::query()
            ->whereNotNull('auction_id')
            ->select('auction_id', DB::raw('COUNT(*) as total'))
            ->groupBy('auction_id')
            ->with('auction')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $avgByStatus = Bid::query()
            ->select(
                'status',
                DB::raw('AVG(price_per_trip_eur) as avg_price_per_trip_eur'),
                DB::raw('AVG(price_per_ton_eur) as avg_price_per_ton_eur'),
                DB::raw('AVG(surcharge_fuel_percent) as avg_surcharge_fuel_percent'),
            )
            ->groupBy('status')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(12)
            ->get();

        return [
            'totalBids' => $totalBids,
            'bidStatusDistribution' => $statusDistribution,
            'bidsPerCarrier' => $bidsPerCarrier,
            'bidsPerAuction' => $bidsPerAuction,
            'bidAveragesByStatus' => $avgByStatus,
        ];
    }

    public function reportContracts(): array
    {
        $totalContracts = Contract::count();
        $statusDistribution = $this->distribution(Contract::query(), 'status');
        $typeDistribution = $this->distribution(Contract::query(), 'contract_type');

        $valueByStatus = Contract::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(total_value_eur) as sum_value'),
                DB::raw('AVG(total_value_eur) as avg_value'),
            )
            ->groupBy('status')
            ->orderByDesc('sum_value')
            ->get();

        $topClients = Contract::query()
            ->select('client_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('client_id')
            ->groupBy('client_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['client', 'currency'])
            ->limit(10)
            ->get();

        $topCarriers = Contract::query()
            ->select('carrier_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('carrier_id')
            ->groupBy('carrier_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['carrier', 'currency'])
            ->limit(10)
            ->get();

        $totals = Contract::query()
            ->selectRaw('SUM(total_value_eur) as sum_value, AVG(total_value_eur) as avg_value, MAX(total_value_eur) as max_value')
            ->first();

        return [
            'totalContracts' => $totalContracts,
            'contractStatusDistribution' => $statusDistribution,
            'contractTypeDistribution' => $typeDistribution,
            'contractValueByStatus' => $valueByStatus,
            'topClientsByContractValue' => $topClients,
            'topCarriersByContractValue' => $topCarriers,
            'contractValueSummary' => $totals,
        ];
    }

    public function reportUsers(): array
    {
        return array_merge($this->userStats(), [
            'usersWithCarrierCount' => User::query()->whereNotNull('carrier_id')->count(),
            'usersWithoutCarrierCount' => User::query()->whereNull('carrier_id')->count(),
            'usersPerCarrier' => User::query()
                ->whereNotNull('carrier_id')
                ->select('carrier_id', DB::raw('COUNT(*) as total'))
                ->groupBy('carrier_id')
                ->with('carrier')
                ->orderByDesc('total')
                ->limit(12)
                ->get(),
        ]);
    }

    public function reportSupportAndNotifications(): array
    {
        $supportByCategory = SupportThread::query()
            ->select('problem_category', DB::raw('COUNT(*) as total'))
            ->groupBy('problem_category')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $supportByType = $this->distribution(SupportThread::query(), 'type');

        return array_merge(
            $this->supportStats(),
            $this->notificationStats(),
            [
                'supportByCategory' => $supportByCategory,
                'supportTypeDistribution' => $supportByType,
            ]
        );
    }

    public function reportMasterData(): array
    {
        $clientsByCountry = Client::query()
            ->select('country', DB::raw('COUNT(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $carriersByCountry = Carrier::query()
            ->select('country', DB::raw('COUNT(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $routesByOriginCountry = LtmRoute::query()
            ->select('origin_country', DB::raw('COUNT(*) as total'))
            ->groupBy('origin_country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $routesByDestinationCountry = LtmRoute::query()
            ->select('destination_country', DB::raw('COUNT(*) as total'))
            ->groupBy('destination_country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'currenciesCount' => Currency::count(),
            'clientsCount' => Client::count(),
            'carriersCount' => Carrier::count(),
            'routesCount' => LtmRoute::count(),
            'auctionsCount' => Auction::count(),
            'lotsCount' => Lot::count(),
            'bidsCount' => Bid::count(),
            'contractsCount' => Contract::count(),
            'clientsByCountry' => $clientsByCountry,
            'carriersByCountry' => $carriersByCountry,
            'routesByOriginCountry' => $routesByOriginCountry,
            'routesByDestinationCountry' => $routesByDestinationCountry,
        ];
    }

    private function userStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('activ', true)->count();
        $inactiveUsers = max($totalUsers - $activeUsers, 0);

        $roleDistribution = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->get();

        $activationDistribution = User::query()
            ->select(DB::raw('activ as label'), DB::raw('COUNT(*) as total'))
            ->groupBy('activ')
            ->get()
            ->map(function ($row) {
                $row->label = $row->label ? 'active' : 'inactive';

                return $row;
            });

        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'userRoleDistribution' => $roleDistribution,
            'userActivationDistribution' => $activationDistribution,
        ];
    }

    private function notificationStats(): array
    {
        $notificationAudience = AppNotification::query()
            ->select('audience', DB::raw('COUNT(*) as total'))
            ->groupBy('audience')
            ->get();

        $notificationTypes = AppNotification::query()
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'notificationAudienceDistribution' => $notificationAudience,
            'notificationTypeDistribution' => $notificationTypes,
        ];
    }

    private function supportStats(): array
    {
        $supportStatus = SupportThread::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $supportSeverity = SupportThread::query()
            ->select('problem_severity', DB::raw('COUNT(*) as total'))
            ->groupBy('problem_severity')
            ->get();

        $supportCategories = SupportThread::query()
            ->select('problem_category', DB::raw('COUNT(*) as total'))
            ->groupBy('problem_category')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'supportThreadsCount' => SupportThread::count(),
            'supportStatusDistribution' => $supportStatus,
            'supportSeverityDistribution' => $supportSeverity,
            'supportCategoryDistribution' => $supportCategories,
        ];
    }

    private function ltmStats(): array
    {
        $ltmKpis = [
            'totalAuctions' => Auction::count(),
            'totalLots' => Lot::count(),
            'totalBids' => Bid::count(),
            'totalContracts' => Contract::count(),
            'totalClients' => Client::count(),
            'totalCarriers' => Carrier::count(),
            'totalRoutes' => LtmRoute::count(),
        ];

        $auctionStatusDistribution = $this->distribution(Auction::query(), 'status');
        $auctionTypeDistribution = $this->distribution(Auction::query(), 'type');
        $bidStatusDistribution = $this->distribution(Bid::query(), 'status');
        $contractStatusDistribution = $this->distribution(Contract::query(), 'status');

        $bidPriceVsFuelScatter = Bid::query()
            ->select('price_per_trip_eur', 'surcharge_fuel_percent')
            ->whereNotNull('price_per_trip_eur')
            ->whereNotNull('surcharge_fuel_percent')
            ->orderByDesc('price_per_trip_eur')
            ->limit(60)
            ->get();

        $ltmTopClients = Contract::query()
            ->select('client_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('client_id')
            ->groupBy('client_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['client', 'currency'])
            ->limit(8)
            ->get();

        $ltmTopCarriers = Contract::query()
            ->select('carrier_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('carrier_id')
            ->groupBy('carrier_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['carrier', 'currency'])
            ->limit(8)
            ->get();

        $ltmTopAuctions = Auction::query()
            ->with(['client', 'currency'])
            ->orderByDesc('estimated_value_eur')
            ->limit(10)
            ->get();

        return [
            'ltmKpis' => $ltmKpis,
            'auctionStatusDistribution' => $auctionStatusDistribution,
            'auctionTypeDistribution' => $auctionTypeDistribution,
            'bidStatusDistribution' => $bidStatusDistribution,
            'contractStatusDistribution' => $contractStatusDistribution,
            'bidPriceVsFuelScatter' => $bidPriceVsFuelScatter,
            'ltmTopClients' => $ltmTopClients,
            'ltmTopCarriers' => $ltmTopCarriers,
            'ltmTopAuctions' => $ltmTopAuctions,
        ];
    }

    private function distribution($query, string $column, int $limit = 12): Collection
    {
        return $query
            ->select($column, DB::raw('COUNT(*) as total'))
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }
}
