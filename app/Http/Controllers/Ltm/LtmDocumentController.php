<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\DocumentRequest;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Client;
use App\Models\Ltm\Contract;
use App\Models\Ltm\Document;
use Illuminate\Http\Request;

class LtmDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['contract', 'auction', 'client', 'carrier']);

        $query->when($request->filled('type'), fn($q) => $q->where('type', $request->type));
        $query->when($request->filled('contract_id'), fn($q) => $q->where('contract_id', $request->contract_id));
        $query->when($request->filled('auction_id'), fn($q) => $q->where('auction_id', $request->auction_id));
        $query->when($request->filled('client_id'), fn($q) => $q->where('client_id', $request->client_id));
        $query->when($request->filled('carrier_id'), fn($q) => $q->where('carrier_id', $request->carrier_id));
        $query->when($request->filled('file_path'), fn($q) => $q->where('file_path', 'like', '%' . $request->file_path . '%'));
        $query->when($request->filled('description'), fn($q) => $q->where('description', 'like', '%' . $request->description . '%'));

        $documents = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        $contracts = Contract::orderBy('contract_number')->get();
        $auctions = Auction::orderBy('auction_number')->get();
        $clients = Client::orderBy('name')->get();
        $carriers = Carrier::orderBy('name')->get();
        $types = Document::select('type')->whereNotNull('type')->distinct()->pluck('type');
        $stats = [
            'total' => Document::count(),
            'contracts' => Document::whereNotNull('contract_id')->count(),
            'types' => $types->count(),
        ];

        return view('ltm.documents.index', [
            'documents' => $documents,
            'contracts' => $contracts,
            'auctions' => $auctions,
            'clients' => $clients,
            'carriers' => $carriers,
            'types' => $types,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $contracts = Contract::orderBy('contract_number')->get();
        $auctions = Auction::orderBy('auction_number')->get();
        $clients = Client::orderBy('name')->get();
        $carriers = Carrier::orderBy('name')->get();
        $types = ['ofertă semnată', 'contract', 'cmr', 'factură', 'proces verbal', 'polita asigurare'];

        return view('ltm.documents.create', compact('contracts', 'auctions', 'clients', 'carriers', 'types'));
    }

    public function store(DocumentRequest $request)
    {
        $document = Document::create($request->validated());

        return redirect()->route('ltm.documente.index')
            ->with('success', __('flash.document_added', ['type' => e($document->type)]));
    }

    public function edit(Document $document)
    {
        $contracts = Contract::orderBy('contract_number')->get();
        $auctions = Auction::orderBy('auction_number')->get();
        $clients = Client::orderBy('name')->get();
        $carriers = Carrier::orderBy('name')->get();
        $types = ['ofertă semnată', 'contract', 'cmr', 'factură', 'proces verbal', 'polita asigurare'];

        return view('ltm.documents.edit', compact('document', 'contracts', 'auctions', 'clients', 'carriers', 'types'));
    }

    public function update(DocumentRequest $request, Document $document)
    {
        $document->update($request->validated());

        return redirect()->route('ltm.documente.index')->with('status', __('flash.document_updated'));
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return back()->with('status', __('flash.document_deleted'));
    }
}
