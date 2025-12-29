<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Contract;
use App\Models\Ltm\Lot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::query()->orderBy('code')->paginate(20);

        return view('settings.currencies.index', [
            'currencies' => $currencies,
        ]);
    }

    public function create()
    {
        $currency = new Currency();

        return view('settings.currencies.create', [
            'currency' => $currency,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:3', 'regex:/^[A-Za-z]{3}$/', 'unique:currencies,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data['code'] = strtoupper($data['code']);

        $currency = Currency::create($data);

        return redirect()
            ->route('settings.currencies.index')
            ->with('success', __('flash.currency_added', ['code' => e($currency->code)]));
    }

    public function edit(Currency $currency)
    {
        return view('settings.currencies.edit', [
            'currency' => $currency,
        ]);
    }

    public function update(Request $request, Currency $currency)
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'size:3',
                'regex:/^[A-Za-z]{3}$/',
                Rule::unique('currencies', 'code')->ignore($currency->id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data['code'] = strtoupper($data['code']);

        $currency->update($data);

        return redirect()
            ->route('settings.currencies.index')
            ->with('status', __('flash.currency_updated', ['code' => e($currency->code)]));
    }

    public function destroy(Currency $currency)
    {
        $inUse = Auction::query()->where('currency_id', $currency->id)->exists()
            || Lot::query()->where('currency_id', $currency->id)->exists()
            || Bid::query()->where('currency_id', $currency->id)->exists()
            || Contract::query()->where('currency_id', $currency->id)->exists();

        if ($inUse) {
            return back()->with('error', __('flash.currency_in_use', ['code' => e($currency->code)]));
        }

        $code = $currency->code;
        $currency->delete();

        return back()->with('status', __('flash.currency_deleted', ['code' => e($code)]));
    }
}

