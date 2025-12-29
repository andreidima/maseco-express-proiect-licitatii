<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use App\Models\Ltm\Document;
use App\Models\Ltm\Lot;
use App\Models\Ltm\Route;
use Illuminate\Support\Facades\DB;

class LtmDashboardController extends Controller
{
    public function index()
    {
        $estimatedValueByCurrency = Auction::query()
            ->select('currency_id', DB::raw('SUM(estimated_value_eur) as total'))
            ->whereNotNull('currency_id')
            ->groupBy('currency_id')
            ->with('currency')
            ->get()
            ->map(fn ($row) => [
                'code' => $row->currency?->code ?? '-',
                'total' => (float) $row->total,
            ])
            ->sortBy('code')
            ->values()
            ->all();

        $contractedValueByCurrency = Contract::query()
            ->select('currency_id', DB::raw('SUM(total_value_eur) as total'))
            ->whereNotNull('currency_id')
            ->groupBy('currency_id')
            ->with('currency')
            ->get()
            ->map(fn ($row) => [
                'code' => $row->currency?->code ?? '-',
                'total' => (float) $row->total,
            ])
            ->sortBy('code')
            ->values()
            ->all();

        $kpis = [
            'totalAuctions' => Auction::count(),
            'openAuctions' => Auction::where('status', 'deschisă')->count(),
            'awardedAuctions' => Auction::where('status', 'atribuită')->count(),
            'estimatedValueByCurrency' => $estimatedValueByCurrency,
            'contractedValueByCurrency' => $contractedValueByCurrency,
            'totalLots' => Lot::count(),
            'activeClients' => Client::has('contracts')->count(),
            'activeCarriers' => Carrier::has('contracts')->count(),
        ];

        $topClients = Contract::select('client_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('client_id')
            ->groupBy('client_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['client', 'currency'])
            ->limit(5)
            ->get();

        $topCarriers = Contract::select('carrier_id', 'currency_id', DB::raw('SUM(total_value_eur) as total_value'))
            ->whereNotNull('carrier_id')
            ->groupBy('carrier_id', 'currency_id')
            ->orderByDesc('total_value')
            ->with(['carrier', 'currency'])
            ->limit(5)
            ->get();

        $auctionStatus = Auction::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $lotGoods = Lot::select('goods_type', DB::raw('COUNT(*) as total'))
            ->groupBy('goods_type')
            ->get();

        $destinationCountries = Route::select('destination_country', DB::raw('COUNT(*) as total'))
            ->groupBy('destination_country')
            ->get();

        $averages = [
            'lotWeight' => Lot::avg('weight_tons'),
            'routeDistance' => Route::avg('distance_km'),
        ];

        $topAuctionsByValue = Auction::with(['client', 'currency'])
            ->orderByDesc('estimated_value_eur')
            ->limit(10)
            ->get();

        $topContractsByValue = Contract::with(['client', 'carrier', 'currency'])
            ->orderByDesc('total_value_eur')
            ->limit(10)
            ->get();

        $bidStatus = Bid::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $documentsByType = Document::select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->get();

        return view('ltm.dashboard', compact(
            'kpis',
            'topClients',
            'topCarriers',
            'auctionStatus',
            'lotGoods',
            'destinationCountries',
            'averages',
            'topAuctionsByValue',
            'topContractsByValue',
            'bidStatus',
            'documentsByType'
        ));
    }
}
