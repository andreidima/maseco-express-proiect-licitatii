<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\ClientRequest;
use App\Models\Ltm\Client;
use Illuminate\Http\Request;

class LtmClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        $query->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'));
        $query->when($request->filled('cui'), fn($q) => $q->where('cui', 'like', '%' . $request->cui . '%'));
        $query->when($request->filled('registration_number'), fn($q) => $q->where('registration_number', 'like', '%' . $request->registration_number . '%'));
        $query->when($request->filled('contact_person'), fn($q) => $q->where('contact_person', 'like', '%' . $request->contact_person . '%'));
        $query->when($request->filled('phone'), fn($q) => $q->where('phone', 'like', '%' . $request->phone . '%'));
        $query->when($request->filled('email'), fn($q) => $q->where('email', 'like', '%' . $request->email . '%'));
        $query->when($request->filled('city'), fn($q) => $q->where('city', 'like', '%' . $request->city . '%'));
        $query->when($request->filled('country'), fn($q) => $q->where('country', $request->country));

        if ($request->filled('payment_terms_min')) {
            $query->where('payment_terms_days', '>=', $request->payment_terms_min);
        }
        if ($request->filled('payment_terms_max')) {
            $query->where('payment_terms_days', '<=', $request->payment_terms_max);
        }

        $clients = $query->orderBy('name')->paginate(20)->appends($request->query());

        $countryOptions = Client::whereNotNull('country')->distinct()->orderBy('country')->pluck('country');
        $stats = [
            'total' => Client::count(),
            'withContracts' => Client::has('contracts')->count(),
            'withAuctions' => Client::has('auctions')->count(),
        ];

        return view('ltm.clients.index', [
            'clients' => $clients,
            'countryOptions' => $countryOptions,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        return view('ltm.clients.create');
    }

    public function store(ClientRequest $request)
    {
        $client = Client::create($request->validated());

        return redirect()->route('ltm.clienti.index')
            ->with('success', __('flash.client_added', ['name' => e($client->name)]));
    }

    public function edit(Client $client)
    {
        return view('ltm.clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()->route('ltm.clienti.index')
            ->with('status', __('flash.client_updated', ['name' => e($client->name)]));
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return back()->with('status', __('flash.client_deleted', ['name' => e($client->name)]));
    }
}
