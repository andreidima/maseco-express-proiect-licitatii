<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/auctions.form_auction_number') }} <span class="text-danger">*</span></label>
        <input type="text" name="auction_number" value="{{ old('auction_number', $auction->auction_number ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-8">
        <label class="form-label">{{ __('ltm/auctions.form_title') }} <span class="text-danger">*</span></label>
        <input type="text" name="title" value="{{ old('title', $auction->title ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/auctions.form_client') }} <span class="text-danger">*</span></label>
        <select name="client_id" class="form-select" required>
            <option value="">{{ __('ltm/auctions.choose_client') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $auction->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/auctions.form_route') }} <span class="text-danger">*</span></label>
        <select name="route_id" class="form-select" required>
            <option value="">{{ __('ltm/auctions.choose_route') }}</option>
            @foreach($routes as $route)
                <option value="{{ $route->id }}" @selected(old('route_id', $auction->route_id ?? '') == $route->id)>{{ $route->code }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/auctions.form_type') }} <span class="text-danger">*</span></label>
        <select name="type" class="form-select" required>
            <option value="">{{ __('ltm/auctions.choose') }}</option>
            @foreach($typeOptions as $type)
                <option value="{{ $type }}" @selected(old('type', $auction->type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/auctions.form_status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="">{{ __('ltm/auctions.choose') }}</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status }}" @selected(old('status', $auction->status ?? '') == $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/auctions.form_estimated_value') }}</label>
        <div class="input-group">
            <input type="number" name="estimated_value_eur" value="{{ old('estimated_value_eur', $auction->estimated_value_eur ?? '') }}" class="form-control" min="0">
            <select name="currency_id" class="form-select" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $auction->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/auctions.form_total_lots') }}</label>
        <input type="number" name="total_lots" value="{{ old('total_lots', $auction->total_lots ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/auctions.form_expected_volume') }}</label>
        <input type="number" step="0.1" name="expected_volume_tons" value="{{ old('expected_volume_tons', $auction->expected_volume_tons ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/auctions.form_description') }}</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $auction->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/auctions.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $auction->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.licitatii.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
