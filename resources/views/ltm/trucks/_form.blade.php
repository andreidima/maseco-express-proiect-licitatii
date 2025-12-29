<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/trucks.form_carrier') }} <span class="text-danger">*</span></label>
        <select name="carrier_id" class="form-select" required>
            <option value="">{{ __('ltm/trucks.option_choose_carrier') }}</option>
            @foreach($carriers as $carrier)
                <option value="{{ $carrier->id }}" @selected(old('carrier_id', $truck->carrier_id ?? '') == $carrier->id)>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/trucks.form_plate') }} <span class="text-danger">*</span></label>
        <input type="text" name="plate_number" value="{{ old('plate_number', $truck->plate_number ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/trucks.form_type') }} <span class="text-danger">*</span></label>
        <select name="truck_type" class="form-select" required>
            <option value="">{{ __('ltm/trucks.option_select') }}</option>
            @foreach($truckTypes as $type)
                <option value="{{ $type }}" @selected(old('truck_type', $truck->truck_type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/trucks.form_max_weight') }}</label>
        <input type="number" step="0.1" name="max_weight_tons" value="{{ old('max_weight_tons', $truck->max_weight_tons ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/trucks.form_euro') }}</label>
        <input type="text" name="euro_class" value="{{ old('euro_class', $truck->euro_class ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/trucks.form_adr') }}</label>
        <select name="has_adr" class="form-select">
            <option value="">{{ __('ltm/trucks.option_select') }}</option>
            <option value="da" @selected(old('has_adr', $truck->has_adr ?? '') === 'da')>{{ __('ltm/trucks.yes') }}</option>
            <option value="nu" @selected(old('has_adr', $truck->has_adr ?? '') === 'nu')>{{ __('ltm/trucks.no') }}</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/trucks.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $truck->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.camioane.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
