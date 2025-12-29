<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\TruckRequest;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Truck;
use Illuminate\Http\Request;

class LtmTruckController extends Controller
{
    public function index(Request $request)
    {
        $query = Truck::with('carrier');

        $query->when($request->filled('carrier_id'), fn($q) => $q->where('carrier_id', $request->carrier_id));
        $query->when($request->filled('plate_number'), fn($q) => $q->where('plate_number', 'like', '%' . $request->plate_number . '%'));
        $query->when($request->filled('truck_type'), fn($q) => $q->where('truck_type', $request->truck_type));
        $query->when($request->filled('euro_class'), fn($q) => $q->where('euro_class', $request->euro_class));
        $query->when($request->filled('has_adr'), fn($q) => $q->where('has_adr', $request->has_adr));

        if ($request->filled('weight_min')) {
            $query->where('max_weight_tons', '>=', $request->weight_min);
        }
        if ($request->filled('weight_max')) {
            $query->where('max_weight_tons', '<=', $request->weight_max);
        }

        $trucks = $query->orderByDesc('id')->paginate(20)->appends($request->query());

        $carriers = Carrier::orderBy('name')->get();
        $truckTypes = Truck::select('truck_type')->whereNotNull('truck_type')->distinct()->pluck('truck_type');
        $euroClasses = Truck::select('euro_class')->whereNotNull('euro_class')->distinct()->pluck('euro_class');
        $stats = [
            'total' => Truck::count(),
            'withAdr' => Truck::where('has_adr', 'da')->count(),
            'avgCapacity' => number_format((float) Truck::avg('max_weight_tons'), 1),
        ];

        return view('ltm.trucks.index', [
            'trucks' => $trucks,
            'carriers' => $carriers,
            'truckTypes' => $truckTypes,
            'euroClasses' => $euroClasses,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $carriers = Carrier::orderBy('name')->get();
        $truckTypes = ['prelată', 'frig', 'cisternă', 'duba', 'platformă'];

        return view('ltm.trucks.create', compact('carriers', 'truckTypes'));
    }

    public function store(TruckRequest $request)
    {
        $truck = Truck::create($request->validated());

        return redirect()->route('ltm.camioane.index')
            ->with('success', __('flash.truck_added', ['plate' => e($truck->plate_number)]));
    }

    public function edit(Truck $truck)
    {
        $carriers = Carrier::orderBy('name')->get();
        $truckTypes = ['prelată', 'frig', 'cisternă', 'duba', 'platformă'];

        return view('ltm.trucks.edit', compact('truck', 'carriers', 'truckTypes'));
    }

    public function update(TruckRequest $request, Truck $truck)
    {
        $truck->update($request->validated());

        return redirect()->route('ltm.camioane.index')
            ->with('status', __('flash.truck_updated', ['plate' => e($truck->plate_number)]));
    }

    public function destroy(Truck $truck)
    {
        $truck->delete();

        return back()->with('status', __('flash.truck_deleted', ['plate' => e($truck->plate_number)]));
    }
}
