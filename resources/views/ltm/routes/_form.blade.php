<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/routes.form_code') }} <span class="text-danger">*</span></label>
        <input type="text" name="code" value="{{ old('code', $route->code ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/routes.form_origin_city') }} <span class="text-danger">*</span></label>
        <input type="text" name="origin_city" value="{{ old('origin_city', $route->origin_city ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/routes.form_origin_country') }} <span class="text-danger">*</span></label>
        <input type="text" name="origin_country" value="{{ old('origin_country', $route->origin_country ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/routes.form_destination_city') }} <span class="text-danger">*</span></label>
        <input type="text" name="destination_city" value="{{ old('destination_city', $route->destination_city ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/routes.form_destination_country') }} <span class="text-danger">*</span></label>
        <input type="text" name="destination_country" value="{{ old('destination_country', $route->destination_country ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/routes.form_distance') }}</label>
        <input type="number" name="distance_km" value="{{ old('distance_km', $route->distance_km ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/routes.form_typical_goods') }}</label>
        <input type="text" name="typical_goods" value="{{ old('typical_goods', $route->typical_goods ?? '') }}" class="form-control" placeholder="{{ __('ltm/routes.form_typical_goods_placeholder') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/routes.form_avg_weight') }}</label>
        <input type="number" step="0.1" name="average_weight_tons" value="{{ old('average_weight_tons', $route->average_weight_tons ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/routes.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $route->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.curse.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
