<?php

namespace App\Http\Controllers\Ltm;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ltm\RouteRequest;
use App\Models\Ltm\Route;
use Illuminate\Http\Request;

class LtmRouteController extends Controller
{
    public function index(Request $request)
    {
        $query = Route::query();

        $query->when($request->filled('code'), fn($q) => $q->where('code', 'like', '%' . $request->code . '%'));
        $query->when($request->filled('origin_city'), fn($q) => $q->where('origin_city', 'like', '%' . $request->origin_city . '%'));
        $query->when($request->filled('origin_country'), fn($q) => $q->where('origin_country', $request->origin_country));
        $query->when($request->filled('destination_city'), fn($q) => $q->where('destination_city', 'like', '%' . $request->destination_city . '%'));
        $query->when($request->filled('destination_country'), fn($q) => $q->where('destination_country', $request->destination_country));
        $query->when($request->filled('typical_goods'), fn($q) => $q->where('typical_goods', 'like', '%' . $request->typical_goods . '%'));

        if ($request->filled('distance_min')) {
            $query->where('distance_km', '>=', $request->distance_min);
        }
        if ($request->filled('distance_max')) {
            $query->where('distance_km', '<=', $request->distance_max);
        }
        if ($request->filled('weight_min')) {
            $query->where('average_weight_tons', '>=', $request->weight_min);
        }
        if ($request->filled('weight_max')) {
            $query->where('average_weight_tons', '<=', $request->weight_max);
        }

        $routes = $query->orderBy('code')->paginate(20)->appends($request->query());

        $countries = Route::select('origin_country')->whereNotNull('origin_country')->distinct()->pluck('origin_country');
        $destinationCountries = Route::select('destination_country')->whereNotNull('destination_country')->distinct()->pluck('destination_country');
        $stats = [
            'total' => Route::count(),
            'withAuctions' => Route::has('auctions')->count(),
            'avgDistance' => number_format((float) Route::avg('distance_km'), 1),
        ];

        return view('ltm.routes.index', [
            'routes' => $routes,
            'countries' => $countries,
            'destinationCountries' => $destinationCountries,
            'filters' => $request->all(),
            'stats' => $stats,
        ]);
    }

    public function create()
    {
        return view('ltm.routes.create');
    }

    public function store(RouteRequest $request)
    {
        $route = Route::create($request->validated());

        return redirect()->route('ltm.curse.index')
            ->with('success', __('flash.route_added', ['code' => e($route->code)]));
    }

    public function edit(Route $route)
    {
        return view('ltm.routes.edit', compact('route'));
    }

    public function update(RouteRequest $request, Route $route)
    {
        $route->update($request->validated());

        return redirect()->route('ltm.curse.index')
            ->with('status', __('flash.route_updated', ['code' => e($route->code)]));
    }

    public function destroy(Route $route)
    {
        $route->delete();

        return back()->with('status', __('flash.route_deleted', ['code' => e($route->code)]));
    }
}
