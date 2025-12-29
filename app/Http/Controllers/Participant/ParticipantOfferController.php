<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Participant\ParticipantBidStoreRequest;
use App\Http\Requests\Participant\ParticipantBidUpdateRequest;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Lot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantOfferController extends Controller
{
    public function index(Request $request)
    {
        $carrierId = Auth::user()->carrier_id;

        $myBidQuery = Bid::query()->where('carrier_id', $carrierId);

        $query = Bid::query()
            ->with(['auction', 'lot', 'currency'])
            ->where('carrier_id', $carrierId);

        $query->when($request->filled('auction_id'), fn ($q) => $q->where('auction_id', $request->auction_id));
        $query->when($request->filled('lot_id'), fn ($q) => $q->where('lot_id', $request->lot_id));

        $bids = $query
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->query());

        $auctionIds = (clone $myBidQuery)->select('auction_id')->distinct()->pluck('auction_id')->filter();
        $lotIds = (clone $myBidQuery)->select('lot_id')->distinct()->pluck('lot_id')->filter();

        $auctions = Auction::query()
            ->when($auctionIds->isNotEmpty(), fn ($q) => $q->whereIn('id', $auctionIds))
            ->orderBy('auction_number')
            ->get();

        $lots = Lot::query()
            ->when($lotIds->isNotEmpty(), fn ($q) => $q->whereIn('id', $lotIds))
            ->orderBy('code')
            ->get();

        return view('participant.offers.index', [
            'bids' => $bids,
            'auctions' => $auctions,
            'lots' => $lots,
            'filters' => $request->all(),
        ]);
    }

    public function create(Request $request)
    {
        $bid = new Bid();

        $openLots = Lot::query()
            ->with('auction')
            ->whereHas('auction', fn ($q) => $q->where('status', 'deschisă'))
            ->orderBy('code')
            ->get();

        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('participant.offers.create', [
            'bid' => $bid,
            'openLots' => $openLots,
            'preselectedLotId' => $request->integer('lot_id') ?: null,
            'currencies' => $currencies,
            'defaultCurrencyId' => $defaultCurrencyId,
        ]);
    }

    public function store(ParticipantBidStoreRequest $request)
    {
        $carrierId = Auth::user()->carrier_id;

        $lot = Lot::query()
            ->with('auction')
            ->findOrFail($request->validated()['lot_id']);

        if (($lot->auction?->status ?? null) !== 'deschisă') {
            abort(403, __('errors.offer_only_open_auctions'));
        }

        $alreadyExists = Bid::query()
            ->where('carrier_id', $carrierId)
            ->where('lot_id', $lot->id)
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', __('flash.offer_exists'));
        }

        $data = $request->validated();
        $data['carrier_id'] = $carrierId;
        $data['auction_id'] = $lot->auction_id;

        Bid::create($data);

        return redirect()
            ->route('participant.oferte.index')
            ->with('success', __('flash.offer_added'));
    }

    public function edit(Bid $bid)
    {
        $carrierId = Auth::user()->carrier_id;

        if ((int) $bid->carrier_id !== (int) $carrierId) {
            abort(403);
        }

        $bid->load(['auction', 'lot']);

        if (($bid->auction?->status ?? null) !== 'deschisă') {
            abort(403, __('errors.offer_edit_only_open_auctions'));
        }

        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('participant.offers.edit', [
            'bid' => $bid,
            'currencies' => $currencies,
            'defaultCurrencyId' => $defaultCurrencyId,
        ]);
    }

    public function update(ParticipantBidUpdateRequest $request, Bid $bid)
    {
        $carrierId = Auth::user()->carrier_id;

        if ((int) $bid->carrier_id !== (int) $carrierId) {
            abort(403);
        }

        $bid->load('auction');

        if (($bid->auction?->status ?? null) !== 'deschisă') {
            abort(403, __('errors.offer_edit_only_open_auctions'));
        }

        $bid->update($request->validated());

        return redirect()
            ->route('participant.oferte.index')
            ->with('status', __('flash.offer_updated'));
    }
}
