<div class="row g-3">
    <div class="col-md-3">
        <label class="form-label">{{ __('settings/currencies.form_code') }} <span class="text-danger">*</span></label>
        <input type="text" name="code" value="{{ old('code', $currency->code ?? '') }}" class="form-control text-uppercase" maxlength="3" required>
        <div class="form-text">{{ __('settings/currencies.form_code_hint') }}</div>
    </div>
    <div class="col-md-9">
        <label class="form-label">{{ __('settings/currencies.form_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $currency->name ?? '') }}" class="form-control" required>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('settings.currencies.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>

