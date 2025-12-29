<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\ContractRequest;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LtmContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['auction', 'carrier', 'client', 'currency']);

        $query->when($request->filled('contract_number'), fn($q) => $q->where('contract_number', 'like', '%' . $request->contract_number . '%'));
        $query->when($request->filled('auction_id'), fn($q) => $q->where('auction_id', $request->auction_id));
        $query->when($request->filled('carrier_id'), fn($q) => $q->where('carrier_id', $request->carrier_id));
        $query->when($request->filled('client_id'), fn($q) => $q->where('client_id', $request->client_id));
        $query->when($request->filled('contract_type'), fn($q) => $q->where('contract_type', $request->contract_type));
        $query->when($request->filled('status'), fn($q) => $q->where('status', $request->status));

        if ($request->filled('total_value_min')) {
            $query->where('total_value_eur', '>=', $request->total_value_min);
        }
        if ($request->filled('total_value_max')) {
            $query->where('total_value_eur', '<=', $request->total_value_max);
        }
        if ($request->filled('valid_from')) {
            $query->whereDate('valid_from', '>=', $request->valid_from);
        }
        if ($request->filled('valid_to')) {
            $query->whereDate('valid_to', '<=', $request->valid_to);
        }

        $contracts = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        $auctions = Auction::orderBy('auction_number')->get();
        $carriers = Carrier::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $contractTypes = Contract::select('contract_type')->whereNotNull('contract_type')->distinct()->pluck('contract_type');
        $statusOptions = Contract::select('status')->whereNotNull('status')->distinct()->pluck('status');

        $valueTotals = Contract::query()
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

        $stats = [
            'total' => Contract::count(),
            'active' => Contract::where('status', 'activ')->count(),
            'valueTotals' => $valueTotals,
        ];

        return view('ltm.contracts.index', [
            'contracts' => $contracts,
            'auctions' => $auctions,
            'carriers' => $carriers,
            'clients' => $clients,
            'contractTypes' => $contractTypes,
            'statusOptions' => $statusOptions,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $contract = new Contract();
        $auctions = Auction::orderBy('auction_number')->get();
        $carriers = Carrier::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $contractTypes = ['cadru', 'spot'];
        $statusOptions = ['activ', 'expirat', 'reziliat'];
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.contracts.create', compact('contract', 'auctions', 'carriers', 'clients', 'contractTypes', 'statusOptions', 'currencies', 'defaultCurrencyId'));
    }

    public function store(ContractRequest $request)
    {
        $contract = Contract::create($request->validated());

        return redirect()->route('ltm.contracte.index')
            ->with('success', __('flash.contract_added', ['number' => e($contract->contract_number)]));
    }

    public function edit(Contract $contract)
    {
        $auctions = Auction::orderBy('auction_number')->get();
        $carriers = Carrier::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $contractTypes = ['cadru', 'spot'];
        $statusOptions = ['activ', 'expirat', 'reziliat'];
        $currencies = Currency::orderBy('code')->get();
        $defaultCurrencyId = $currencies->firstWhere('code', 'EUR')?->id;

        return view('ltm.contracts.edit', compact('contract', 'auctions', 'carriers', 'clients', 'contractTypes', 'statusOptions', 'currencies', 'defaultCurrencyId'));
    }

    public function update(ContractRequest $request, Contract $contract)
    {
        $contract->update($request->validated());

        return redirect()->route('ltm.contracte.index')
            ->with('status', __('flash.contract_updated', ['number' => e($contract->contract_number)]));
    }

    public function destroy(Contract $contract)
    {
        $contract->delete();

        return back()->with('status', __('flash.contract_deleted', ['number' => e($contract->contract_number)]));
    }
}
