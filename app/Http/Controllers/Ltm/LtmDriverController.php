<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\DriverRequest;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Driver;
use Illuminate\Http\Request;

class LtmDriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::with('carrier');

        $query->when($request->filled('carrier_id'), fn($q) => $q->where('carrier_id', $request->carrier_id));
        $query->when($request->filled('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'));
        $query->when($request->filled('phone'), fn($q) => $q->where('phone', 'like', '%' . $request->phone . '%'));
        $query->when($request->filled('email'), fn($q) => $q->where('email', 'like', '%' . $request->email . '%'));
        $query->when($request->filled('languages'), fn($q) => $q->where('languages', 'like', '%' . $request->languages . '%'));
        $query->when($request->filled('has_adr'), fn($q) => $q->where('has_adr', $request->has_adr));

        if ($request->filled('experience_min')) {
            $query->where('experience_years', '>=', $request->experience_min);
        }
        if ($request->filled('experience_max')) {
            $query->where('experience_years', '<=', $request->experience_max);
        }

        $drivers = $query->orderBy('name')->paginate(20)->appends($request->query());

        $carriers = Carrier::orderBy('name')->get();
        $adrOptions = Driver::select('has_adr')->whereNotNull('has_adr')->distinct()->pluck('has_adr');
        $stats = [
            'total' => Driver::count(),
            'withAdr' => Driver::where('has_adr', 'da')->count(),
            'avgExperience' => number_format((float) Driver::avg('experience_years'), 1),
        ];

        return view('ltm.drivers.index', [
            'drivers' => $drivers,
            'carriers' => $carriers,
            'adrOptions' => $adrOptions,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        $carriers = Carrier::orderBy('name')->get();
        $adrOptions = ['da', 'nu'];

        return view('ltm.drivers.create', compact('carriers', 'adrOptions'));
    }

    public function store(DriverRequest $request)
    {
        $driver = Driver::create($request->validated());

        return redirect()->route('ltm.soferi.index')
            ->with('success', __('flash.driver_added', ['name' => e($driver->name)]));
    }

    public function edit(Driver $driver)
    {
        $carriers = Carrier::orderBy('name')->get();
        $adrOptions = ['da', 'nu'];

        return view('ltm.drivers.edit', compact('driver', 'carriers', 'adrOptions'));
    }

    public function update(DriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());

        return redirect()->route('ltm.soferi.index')
            ->with('status', __('flash.driver_updated', ['name' => e($driver->name)]));
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return back()->with('status', __('flash.driver_deleted', ['name' => e($driver->name)]));
    }
}
