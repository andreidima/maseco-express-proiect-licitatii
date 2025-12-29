<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_carrier') }} <span class="text-danger">*</span></label>
        <select name="carrier_id" class="form-select" required>
            <option value="">{{ __('ltm/drivers.option_choose_carrier') }}</option>
            @foreach($carriers as $carrier)
                <option value="{{ $carrier->id }}" @selected(old('carrier_id', $driver->carrier_id ?? '') == $carrier->id)>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $driver->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone', $driver->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_email') }}</label>
        <input type="email" name="email" value="{{ old('email', $driver->email ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_languages') }}</label>
        <input type="text" name="languages" value="{{ old('languages', $driver->languages ?? '') }}" class="form-control" placeholder="{{ __('ltm/drivers.form_languages_placeholder') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_experience') }}</label>
        <input type="number" name="experience_years" value="{{ old('experience_years', $driver->experience_years ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/drivers.form_adr') }}</label>
        <select name="has_adr" class="form-select">
            <option value="">{{ __('ltm/drivers.option_select') }}</option>
            <option value="da" @selected(old('has_adr', $driver->has_adr ?? '') === 'da')>{{ __('ltm/drivers.yes') }}</option>
            <option value="nu" @selected(old('has_adr', $driver->has_adr ?? '') === 'nu')>{{ __('ltm/drivers.no') }}</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/drivers.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $driver->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.soferi.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
