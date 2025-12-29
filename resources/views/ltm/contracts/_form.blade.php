<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_auction') }} <span class="text-danger">*</span></label>
        <select name="auction_id" class="form-select" required>
            <option value="">{{ __('ltm/contracts.option_choose_auction') }}</option>
            @foreach($auctions as $auction)
                <option value="{{ $auction->id }}" @selected(old('auction_id', $contract->auction_id ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_client') }} <span class="text-danger">*</span></label>
        <select name="client_id" class="form-select" required>
            <option value="">{{ __('ltm/contracts.option_choose_client') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $contract->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_carrier') }} <span class="text-danger">*</span></label>
        <select name="carrier_id" class="form-select" required>
            <option value="">{{ __('ltm/contracts.option_choose_carrier') }}</option>
            @foreach($carriers as $carrier)
                <option value="{{ $carrier->id }}" @selected(old('carrier_id', $contract->carrier_id ?? '') == $carrier->id)>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_contract_number') }} <span class="text-danger">*</span></label>
        <input type="text" name="contract_number" value="{{ old('contract_number', $contract->contract_number ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_contract_type') }} <span class="text-danger">*</span></label>
        <select name="contract_type" class="form-select" required>
            <option value="">{{ __('ltm/contracts.option_select') }}</option>
            @foreach($contractTypes as $type)
                <option value="{{ $type }}" @selected(old('contract_type', $contract->contract_type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_status') }} <span class="text-danger">*</span></label>
        <select name="status" class="form-select" required>
            <option value="">{{ __('ltm/contracts.option_select') }}</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status }}" @selected(old('status', $contract->status ?? '') == $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="currency_id" id="currency_contract" value="{{ old('currency_id', $contract->currency_id ?? $defaultCurrencyId) }}">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_value') }} <span class="text-danger">*</span></label>
        <div class="input-group">
            <input type="number" name="total_value_eur" value="{{ old('total_value_eur', $contract->total_value_eur ?? '') }}" class="form-control" min="0" required>
            <select class="form-select js-currency-select" data-currency-group="contract" data-currency-hidden="currency_contract" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $contract->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_avg_trip_price') }}</label>
        <div class="input-group">
            <input type="number" step="0.01" name="average_price_per_trip_eur" value="{{ old('average_price_per_trip_eur', $contract->average_price_per_trip_eur ?? '') }}" class="form-control" min="0">
            <select class="form-select js-currency-select" data-currency-group="contract" data-currency-hidden="currency_contract" style="max-width: 7rem">
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $contract->currency_id ?? $defaultCurrencyId) == $currency->id)>{{ $currency->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_valid_from') }}</label>
        <input type="date" name="valid_from" value="{{ old('valid_from', $contract->valid_from ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/contracts.form_valid_to') }}</label>
        <input type="date" name="valid_to" value="{{ old('valid_to', $contract->valid_to ?? '') }}" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/contracts.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $contract->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.contracte.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
