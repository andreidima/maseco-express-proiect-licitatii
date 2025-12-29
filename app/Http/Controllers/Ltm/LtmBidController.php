<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\BidRequest;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LtmBidController extends Controller
{
    public function index(Request $request)
    {
        $query = Bid::with(['auction', 'lot', 'carrier', 'currency']);

        $query->when($request->filled('auction_id'), fn($q) => $q->where('auction_id', $request->auction_id));
        $query->when($request->filled('lot_id'), fn($q) => $q->where('lot_id', $request->lot_id));
        $query->when($request->filled('carrier_id'), fn($q) => $q->where('carrier_id', $request->carrier_id));
        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        if ($request->filled('price_trip_min')) {
            $query->where('price_per_trip_eur', '>=', $request->price_trip_min);
        }
        if ($request->filled('price_trip_max')) {
            $query->where('price_per_trip_eur', '<=', $request->price_trip_max);
        }
        if ($request->filled('price_ton_min')) {
            $query->where('price_per_ton_eur', '>=', $request->price_ton_min);
        }
        if ($request->filled('price_ton_max')) {
            $query->where('price_per_ton_eur', '<=', $request->price_ton_max);
        }
        if ($request->filled('payment_terms_min')) {
            $query->where('payment_terms_days', '>=', $request->payment_terms_min);
        }
        if ($request->filled('payment_terms_max')) {
            $query->where('payment_terms_days', '<=', $request->payment_terms_max);
        }

        $bids = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        $auctions = Auction::orderBy('auction_number')->get();
        $lots = Lot::orderBy('code')->get();
        $carriers = Carrier::orderBy('name')->get();
        $statusOptions = Bid::select('status')->whereNotNull('status')->distinct()->pluck('status');

        $averageTripByCurrency = Bid::query()
            ->select('currency_id', DB::raw('AVG(price_per_trip_eur) as avg_trip'))
            ->whereNotNull('currency_id')
            ->groupBy('currency_id')
            ->with('currency')
            ->get()
            ->map(fn ($row) => [
                'code' => $row->currency?->code ?? '-',
                'avg' => (float) $row->avg_trip,
            ])
            ->sortBy('code')
            ->values()
            ->all();

        $stats = [
            'total' => Bid::count(),
            'accepted' => Bid::where('status', 'acceptată')->count(),
            'averageTripByCurrency' => $averageTripByCurrency,
        ];

        return view('ltm.bids.index', [
            'bids' => $bids,
            'auctions' => $auctions,
            'lots' => $lots,
            'carriers' => $carriers,
            'statusOptions' => $statusOptions,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $bid = new Bid();
        $auctions = Auction::orderBy('auction_number')->get();
        $lots = Lot::orderBy('code')->get();
        $carriers = Carrier::orderBy('name')->get();
        $statusOptions = ['în analiză', 'acceptată', 'respinsă'];
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.bids.create', compact('bid', 'auctions', 'lots', 'carriers', 'statusOptions', 'currencies', 'defaultCurrencyId'));
    }

    public function store(BidRequest $request)
    {
        $data = $request->validated();
        $lot = Lot::find($data['lot_id']);
        if ($lot && $lot->auction_id) {
            $data['auction_id'] = $lot->auction_id;
        }

        $bid = Bid::create($data);

        return redirect()->route('ltm.oferte.index')->with('success', __('flash.bid_added', [
            'code' => e($bid->lot->code ?? ''),
        ]));
    }

    public function edit(Bid $bid)
    {
        $auctions = Auction::orderBy('auction_number')->get();
        $lots = Lot::orderBy('code')->get();
        $carriers = Carrier::orderBy('name')->get();
        $statusOptions = ['în analiză', 'acceptată', 'respinsă'];
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.bids.edit', compact('bid', 'auctions', 'lots', 'carriers', 'statusOptions', 'currencies', 'defaultCurrencyId'));
    }

    public function update(BidRequest $request, Bid $bid)
    {
        $data = $request->validated();
        $lot = Lot::find($data['lot_id']);
        if ($lot && $lot->auction_id) {
            $data['auction_id'] = $lot->auction_id;
        }

        $bid->update($data);

        return redirect()->route('ltm.oferte.index')->with('status', __('flash.bid_updated'));
    }

    public function destroy(Bid $bid)
    {
        $bid->delete();

        return back()->with('status', __('flash.bid_deleted'));
    }
}
