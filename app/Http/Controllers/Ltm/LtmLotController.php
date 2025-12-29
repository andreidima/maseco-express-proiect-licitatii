<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\LotRequest;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Lot;
use Illuminate\Http\Request;

class LtmLotController extends Controller
{
    public function index(Request $request)
    {
        $query = Lot::with(['auction', 'currency']);

        $query->when($request->filled('code'), fn($q) => $q->where('code', 'like', '%' . $request->code . '%'));
        $query->when($request->filled('auction_id'), fn($q) => $q->where('auction_id', $request->auction_id));
        $query->when($request->filled('goods_type'), fn($q) => $q->where('goods_type', $request->goods_type));
        $query->when($request->filled('pickup_city'), fn($q) => $q->where('pickup_city', 'like', '%' . $request->pickup_city . '%'));
        $query->when($request->filled('delivery_city'), fn($q) => $q->where('delivery_city', 'like', '%' . $request->delivery_city . '%'));

        if ($request->filled('weight_min')) {
            $query->where('weight_tons', '>=', $request->weight_min);
        }
        if ($request->filled('weight_max')) {
            $query->where('weight_tons', '<=', $request->weight_max);
        }
        if ($request->filled('pallets_min')) {
            $query->where('pallets', '>=', $request->pallets_min);
        }
        if ($request->filled('pallets_max')) {
            $query->where('pallets', '<=', $request->pallets_max);
        }
        if ($request->filled('trips_min')) {
            $query->where('trips_per_month', '>=', $request->trips_min);
        }
        if ($request->filled('trips_max')) {
            $query->where('trips_per_month', '<=', $request->trips_max);
        }
        if ($request->filled('budget_min')) {
            $query->where('max_budget_eur', '>=', $request->budget_min);
        }
        if ($request->filled('budget_max')) {
            $query->where('max_budget_eur', '<=', $request->budget_max);
        }

        $lots = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        $auctions = Auction::orderBy('auction_number')->get();
        $goodsTypes = Lot::select('goods_type')->whereNotNull('goods_type')->distinct()->pluck('goods_type');
        $stats = [
            'total' => Lot::count(),
            'avgWeight' => number_format((float) Lot::avg('weight_tons'), 1),
            'avgTrips' => number_format((float) Lot::avg('trips_per_month'), 1),
        ];

        return view('ltm.lots.index', [
            'lots' => $lots,
            'auctions' => $auctions,
            'goodsTypes' => $goodsTypes,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $lot = new Lot();
        $auctions = Auction::orderBy('auction_number')->get();
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.lots.create', compact('lot', 'auctions', 'currencies', 'defaultCurrencyId'));
    }

    public function store(LotRequest $request)
    {
        $lot = Lot::create($request->validated());

        return redirect()->route('ltm.loturi.index')
            ->with('success', __('flash.lot_added', ['code' => e($lot->code)]));
    }

    public function edit(Lot $lot)
    {
        $auctions = Auction::orderBy('auction_number')->get();
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.lots.edit', compact('lot', 'auctions', 'currencies', 'defaultCurrencyId'));
    }

    public function update(LotRequest $request, Lot $lot)
    {
        $lot->update($request->validated());

        return redirect()->route('ltm.loturi.index')
            ->with('status', __('flash.lot_updated', ['code' => e($lot->code)]));
    }

    public function destroy(Lot $lot)
    {
        $lot->delete();

        return back()->with('status', __('flash.lot_deleted', ['code' => e($lot->code)]));
    }
}
