<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_auction') }} <span class="text-danger">*</span></label>
        <select name="auction_id" class="form-select" required>
            <option value="">{{ __('ltm/bids.option_choose_auction') }}</option>
            @foreach($auctions as $auction)
                <option value="{{ $auction->id }}" @selected(old('auction_id', $bid->auction_id ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_lot') }} <span class="text-danger">*</span></label>
        <select name="lot_id" class="form-select" required>
            <option value="">{{ __('ltm/bids.option_choose_lot') }}</option>
            @foreach($lots as $lot)
                <option value="{{ $lot->id }}" @selected(old('lot_id', $bid->lot_id ?? '') == $lot->id)>{{ $lot->code }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_carrier') }} <span class="text-danger">*</span></label>
        <select name="carrier_id" class="form-select" required>
            <option value="">{{ __('ltm/bids.option_choose_carrier') }}</option>
            @foreach($carriers as $carrier)
                <option value="{{ $carrier->id }}" @selected(old('carrier_id', $bid->carrier_id ?? '') == $carrier->id)>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="currency_id" id="currency_bid" value="{{ old('currency_id', $bid->currency_id ?? $defaultCurrencyId) }}">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_price_trip') }} <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" name="price_per_trip_eur" value="{{ old('price_per_trip_eur', $bid->price_per_trip_eur ?? '') }}" class="form-control" min="0" required>
            <select class="form-select js-currency-select" data-currency-group="bid" data-currency-hidden="currency_bid" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $bid->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_price_ton') }}</label>
        <div class="input-group">
            <input type="number" step="0.01" name="price_per_ton_eur" value="{{ old('price_per_ton_eur', $bid->price_per_ton_eur ?? '') }}" class="form-control" min="0">
            <select class="form-select js-currency-select" data-currency-group="bid" data-currency-hidden="currency_bid" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $bid->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_surcharge_fuel') }}</label>
        <input type="number" step="0.01" name="surcharge_fuel_percent" value="{{ old('surcharge_fuel_percent', $bid->surcharge_fuel_percent ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_payment_terms') }}</label>
        <input type="number" name="payment_terms_days" value="{{ old('payment_terms_days', $bid->payment_terms_days ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/bids.form_status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="">{{ __('ltm/bids.option_select') }}</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status }}" @selected(old('status', $bid->status ?? '') == $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/bids.form_notes') }}</label>
        <textarea name="internal_comment" rows="3" class="form-control">{{ old('internal_comment', $bid->internal_comment ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.oferte.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
