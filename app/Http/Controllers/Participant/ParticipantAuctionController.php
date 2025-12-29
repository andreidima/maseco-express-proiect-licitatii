<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Client;
use App\Models\Ltm\Route as LtmRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantAuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::query()->with(['client', 'route']);

        $query->when($request->filled('auction_number'), fn ($q) => $q->where('auction_number', 'like', '%' . $request->auction_number . '%'));
        $query->when($request->filled('title'), fn ($q) => $q->where('title', 'like', '%' . $request->title . '%'));
        $query->when($request->filled('client_id'), fn ($q) => $q->where('client_id', $request->client_id));
        $query->when($request->filled('route_id'), fn ($q) => $q->where('route_id', $request->route_id));
        $query->when($request->filled('status'), fn ($q) => $q->where('status', $request->status));

        $auctions = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->query());

        $clients = Client::orderBy('name')->get();
        $routes = LtmRoute::orderBy('code')->get();
        $statusOptions = Auction::select('status')->whereNotNull('status')->distinct()->pluck('status');

        return view('participant.auctions.index', [
            'auctions' => $auctions,
            'clients' => $clients,
            'routes' => $routes,
            'statusOptions' => $statusOptions,
            'filters' => $request->all(),
        ]);
    }

    public function show(Auction $auction)
    {
        $auction->load(['client', 'route', 'lots']);

        $carrierId = Auth::user()?->carrier_id;
        $existingBids = collect();

        if ($carrierId) {
            $existingBids = $auction->bids()
                ->where('carrier_id', $carrierId)
                ->get()
                ->keyBy('lot_id');
        }

        $lots = $auction->lots->sortBy('code')->values();

        return view('participant.auctions.show', [
            'auction' => $auction,
            'lots' => $lots,
            'existingBids' => $existingBids,
        ]);
    }
}
