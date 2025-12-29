<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/carriers.form_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $carrier->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/carriers.form_cui') }} <span class="text-danger">*</span></label>
        <input type="text" name="cui" value="{{ old('cui', $carrier->cui ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/carriers.form_registration_number') }}</label>
        <input type="text" name="registration_number" value="{{ old('registration_number', $carrier->registration_number ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/carriers.form_contact_person') }}</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $carrier->contact_person ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/carriers.form_phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone', $carrier->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/carriers.form_email') }}</label>
        <input type="email" name="email" value="{{ old('email', $carrier->email ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/carriers.form_city') }}</label>
        <input type="text" name="city" value="{{ old('city', $carrier->city ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/carriers.form_country') }}</label>
        <input type="text" name="country" value="{{ old('country', $carrier->country ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/carriers.form_rating') }}</label>
        <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $carrier->rating ?? '') }}" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/carriers.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $carrier->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.transportatori.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
