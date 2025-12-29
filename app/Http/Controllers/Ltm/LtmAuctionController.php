<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\AuctionRequest;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Client;
use App\Models\Ltm\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LtmAuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::with(['client', 'route', 'currency']);

        $query->when($request->filled('auction_number'), fn($q) => $q->where('auction_number', 'like', '%' . $request->auction_number . '%'));
        $query->when($request->filled('title'), fn($q) => $q->where('title', 'like', '%' . $request->title . '%'));
        $query->when($request->filled('client_id'), fn($q) => $q->where('client_id', $request->client_id));
        $query->when($request->filled('route_id'), fn($q) => $q->where('route_id', $request->route_id));
        $query->when($request->filled('type'), fn($q) => $q->where('type', $request->type));
        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        if ($request->filled('estimated_value_min')) {
            $query->where('estimated_value_eur', '>=', $request->estimated_value_min);
        }
        if ($request->filled('estimated_value_max')) {
            $query->where('estimated_value_eur', '<=', $request->estimated_value_max);
        }
        if ($request->filled('total_lots_min')) {
            $query->where('total_lots', '>=', $request->total_lots_min);
        }
        if ($request->filled('total_lots_max')) {
            $query->where('total_lots', '<=', $request->total_lots_max);
        }
        if ($request->filled('expected_volume_min')) {
            $query->where('expected_volume_tons', '>=', $request->expected_volume_min);
        }
        if ($request->filled('expected_volume_max')) {
            $query->where('expected_volume_tons', '<=', $request->expected_volume_max);
        }

        $auctions = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        $clients = Client::orderBy('name')->get();
        $routes = Route::orderBy('code')->get();
        $typeOptions = Auction::select('type')->whereNotNull('type')->distinct()->pluck('type');
        $statusOptions = Auction::select('status')->whereNotNull('status')->distinct()->pluck('status');

        $estTotals = Auction::query()
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

        $stats = [
            'total' => Auction::count(),
            'open' => Auction::where('status', 'deschisă')->count(),
            'awarded' => Auction::where('status', 'atribuită')->count(),
            'estTotals' => $estTotals,
        ];

        return view('ltm.auctions.index', [
            'auctions' => $auctions,
            'clients' => $clients,
            'routes' => $routes,
            'typeOptions' => $typeOptions,
            'statusOptions' => $statusOptions,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $auction = new Auction();
        $clients = Client::orderBy('name')->get();
        $routes = Route::orderBy('code')->get();
        $typeOptions = ['licitație spot', 'contract anual', 'mini licitație', 'tender trimestrial'];
        $statusOptions = ['în pregătire', 'deschisă', 'în evaluare', 'atribuită', 'anulată'];
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.auctions.create', compact('auction', 'clients', 'routes', 'typeOptions', 'statusOptions', 'currencies', 'defaultCurrencyId'));
    }

    public function store(AuctionRequest $request)
    {
        $auction = Auction::create($request->validated());

        return redirect()->route('ltm.licitatii.index')
            ->with('success', __('flash.auction_added', ['number' => e($auction->auction_number)]));
    }

    public function edit(Auction $auction)
    {
        $clients = Client::orderBy('name')->get();
        $routes = Route::orderBy('code')->get();
        $typeOptions = ['licitație spot', 'contract anual', 'mini licitație', 'tender trimestrial'];
        $statusOptions = ['în pregătire', 'deschisă', 'în evaluare', 'atribuită', 'anulată'];
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.auctions.edit', compact('auction', 'clients', 'routes', 'typeOptions', 'statusOptions', 'currencies', 'defaultCurrencyId'));
    }

    public function update(AuctionRequest $request, Auction $auction)
    {
        $auction->update($request->validated());

        return redirect()->route('ltm.licitatii.index')
            ->with('status', __('flash.auction_updated', ['number' => e($auction->auction_number)]));
    }

    public function destroy(Auction $auction)
    {
        $auction->delete();

        return back()->with('status', __('flash.auction_deleted', ['number' => e($auction->auction_number)]));
    }
}
