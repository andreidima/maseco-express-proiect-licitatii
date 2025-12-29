<div class="row g-3">
    @if(!empty($openLots))
        <div class="col-12">
            <label class="form-label mb-1">{{ __('participant/offers.field_lot') }}<span class="text-danger">*</span></label>
            <select name="lot_id" class="form-select" required>
                <option value="">{{ __('participant/offers.choose') }}</option>
                @foreach($openLots as $lot)
                    <option value="{{ $lot->id }}" @selected(old('lot_id', $preselectedLotId ?? '') == $lot->id)>
                        {{ ($lot->auction->auction_number ?? '-') . ' • ' . ($lot->code ?? '-') . ' • ' . ($lot->description ?? '') }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @if(!empty($bid))
        <div class="col-12">
            <div class="text-muted small">{{ __('participant/offers.field_lot') }}</div>
            <div class="fw-bold">
                {{ ($bid->auction->auction_number ?? '-') . ' • ' . ($bid->lot->code ?? '-') . ' • ' . ($bid->lot->description ?? '') }}
            </div>
        </div>
    @endif

    <input type="hidden" name="currency_id" id="currency_offer" value="{{ old('currency_id', $bid->currency_id ?? $defaultCurrencyId) }}">
    <div class="col-md-4">
        <label class="form-label mb-1">{{ __('participant/offers.field_price_trip') }}<span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" name="price_per_trip_eur" class="form-control"
                value="{{ old('price_per_trip_eur', $bid->price_per_trip_eur ?? '') }}" required>
            <select class="form-select js-currency-select" data-currency-group="offer" data-currency-hidden="currency_offer" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $bid->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label mb-1">{{ __('participant/offers.field_price_ton') }}</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0" name="price_per_ton_eur" class="form-control"
                value="{{ old('price_per_ton_eur', $bid->price_per_ton_eur ?? '') }}">
            <select class="form-select js-currency-select" data-currency-group="offer" data-currency-hidden="currency_offer" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $bid->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label mb-1">{{ __('participant/offers.field_fuel_surcharge') }}</label>
        <input type="number" step="0.01" min="0" name="surcharge_fuel_percent" class="form-control"
            value="{{ old('surcharge_fuel_percent', $bid->surcharge_fuel_percent ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label mb-1">{{ __('participant/offers.field_payment_terms') }}</label>
        <input type="number" step="1" min="0" name="payment_terms_days" class="form-control"
            value="{{ old('payment_terms_days', $bid->payment_terms_days ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label mb-1">{{ __('participant/offers.field_comment') }}</label>
        <textarea name="internal_comment" class="form-control" rows="3">{{ old('internal_comment', $bid->internal_comment ?? '') }}</textarea>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button class="btn btn-primary text-white" type="submit">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText ?? __('participant/offers.save') }}
    </button>
    <a class="btn btn-outline-secondary" href="{{ route('participant.oferte.index') }}">{{ __('participant/offers.cancel') }}</a>
</div>
