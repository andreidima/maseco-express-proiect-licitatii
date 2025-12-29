<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/lots.form_auction') }} <span class="text-danger">*</span></label>
        <select name="auction_id" class="form-select" required>
            <option value="">{{ __('ltm/lots.option_choose_auction') }}</option>
            @foreach($auctions as $auction)
                <option value="{{ $auction->id }}" @selected(old('auction_id', $lot->auction_id ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_code') }} <span class="text-danger">*</span></label>
        <input type="text" name="code" value="{{ old('code', $lot->code ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_goods_type') }} <span class="text-danger">*</span></label>
        <input type="text" name="goods_type" value="{{ old('goods_type', $lot->goods_type ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/lots.form_description') }} <span class="text-danger">*</span></label>
        <input type="text" name="description" value="{{ old('description', $lot->description ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_weight') }}</label>
        <input type="number" step="0.1" name="weight_tons" value="{{ old('weight_tons', $lot->weight_tons ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_pallets') }}</label>
        <input type="number" name="pallets" value="{{ old('pallets', $lot->pallets ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_trips_per_month') }}</label>
        <input type="number" name="trips_per_month" value="{{ old('trips_per_month', $lot->trips_per_month ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_max_budget') }}</label>
        <div class="input-group">
            <input type="number" name="max_budget_eur" value="{{ old('max_budget_eur', $lot->max_budget_eur ?? '') }}" class="form-control" min="0">
            <select name="currency_id" class="form-select" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $lot->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_pickup_city') }}</label>
        <input type="text" name="pickup_city" value="{{ old('pickup_city', $lot->pickup_city ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_pickup_country') }}</label>
        <input type="text" name="pickup_country" value="{{ old('pickup_country', $lot->pickup_country ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_delivery_city') }}</label>
        <input type="text" name="delivery_city" value="{{ old('delivery_city', $lot->delivery_city ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/lots.form_delivery_country') }}</label>
        <input type="text" name="delivery_country" value="{{ old('delivery_country', $lot->delivery_country ?? '') }}" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/lots.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $lot->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.loturi.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
