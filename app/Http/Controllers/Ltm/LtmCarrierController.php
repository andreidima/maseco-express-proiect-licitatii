<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\CarrierRequest;
use App\Models\Ltm\Carrier;
use Illuminate\Http\Request;

class LtmCarrierController extends Controller
{
    public function index(Request $request)
    {
        $query = Carrier::query();

        $query->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'));
        $query->when($request->filled('cui'), fn($q) => $q->where('cui', 'like', '%' . $request->cui . '%'));
        $query->when($request->filled('registration_number'), fn($q) => $q->where('registration_number', 'like', '%' . $request->registration_number . '%'));
        $query->when($request->filled('contact_person'), fn($q) => $q->where('contact_person', 'like', '%' . $request->contact_person . '%'));
        $query->when($request->filled('phone'), fn($q) => $q->where('phone', 'like', '%' . $request->phone . '%'));
        $query->when($request->filled('email'), fn($q) => $q->where('email', 'like', '%' . $request->email . '%'));
        $query->when($request->filled('city'), fn($q) => $q->where('city', 'like', '%' . $request->city . '%'));
        $query->when($request->filled('country'), fn($q) => $q->where('country', $request->country));

        if ($request->filled('rating_min')) {
            $query->where('rating', '>=', $request->rating_min);
        }
        if ($request->filled('rating_max')) {
            $query->where('rating', '<=', $request->rating_max);
        }

        $carriers = $query->orderBy('name')->paginate(20)->appends($request->query());
        $countryOptions = Carrier::whereNotNull('country')->distinct()->orderBy('country')->pluck('country');
        $stats = [
            'total' => Carrier::count(),
            'withContracts' => Carrier::has('contracts')->count(),
            'withBids' => Carrier::has('bids')->count(),
        ];

        return view('ltm.carriers.index', [
            'carriers' => $carriers,
            'countryOptions' => $countryOptions,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        return view('ltm.carriers.create');
    }

    public function store(CarrierRequest $request)
    {
        $carrier = Carrier::create($request->validated());

        return redirect()->route('ltm.transportatori.index')
            ->with('success', __('flash.carrier_added', ['name' => e($carrier->name)]));
    }

    public function edit(Carrier $carrier)
    {
        return view('ltm.carriers.edit', compact('carrier'));
    }

    public function update(CarrierRequest $request, Carrier $carrier)
    {
        $carrier->update($request->validated());

        return redirect()->route('ltm.transportatori.index')
            ->with('status', __('flash.carrier_updated', ['name' => e($carrier->name)]));
    }

    public function destroy(Carrier $carrier)
    {
        $carrier->delete();

        return back()->with('status', __('flash.carrier_deleted', ['name' => e($carrier->name)]));
    }
}
