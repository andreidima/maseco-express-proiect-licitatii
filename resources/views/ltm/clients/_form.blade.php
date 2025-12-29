<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('ltm/clients.form_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/clients.form_cui') }} <span class="text-danger">*</span></label>
        <input type="text" name="cui" value="{{ old('cui', $client->cui ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ltm/clients.form_registration_number') }}</label>
        <input type="text" name="registration_number" value="{{ old('registration_number', $client->registration_number ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/clients.form_contact_person') }}</label>
        <input type="text" name="contact_person" value="{{ old('contact_person', $client->contact_person ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/clients.form_phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/clients.form_email') }}</label>
        <input type="email" name="email" value="{{ old('email', $client->email ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/clients.form_city') }}</label>
        <input type="text" name="city" value="{{ old('city', $client->city ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/clients.form_country') }}</label>
        <input type="text" name="country" value="{{ old('country', $client->country ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/clients.form_payment_terms') }}</label>
        <input type="number" name="payment_terms_days" value="{{ old('payment_terms_days', $client->payment_terms_days ?? '') }}" class="form-control" min="0">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/clients.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $client->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.clienti.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
