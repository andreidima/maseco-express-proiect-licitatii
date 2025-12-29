<div class="row mb-4 pt-2 rounded-3" style="border:1px solid #e9ecef; border-left:0.25rem darkcyan solid; background-color:rgb(241, 250, 250)">
    <div class="col-lg-6 mb-4">
        <label for="name" class="mb-0 ps-3">{{ __('users.label_name') }}<span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('name') ? 'is-invalid' : '' }}"
            name="name"
            id="name"
            value="{{ old('name', $user->name ?? '') }}"
            autocomplete="name"
            required>
    </div>
    <div class="col-lg-6 mb-4">
        <label for="telefon" class="mb-0 ps-3">{{ __('users.label_phone') }}</label>
        <input
            type="text"
            class="form-control bg-white rounded-3 {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
            name="telefon"
            id="telefon"
            value="{{ old('telefon', $user->telefon ?? '') }}">
    </div>
    <div class="col-lg-6 mb-4">
        <label for="email" class="mb-0 ps-3">{{ __('users.label_email') }}<span class="text-danger">*</span></label>
        <input
            type="email"
            class="form-control bg-white rounded-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
            name="email"
            id="email"
            autocomplete="email"
            value="{{ old('email', $user->email ?? '') }}"
            required>
    </div>
    <div class="col-lg-3 mb-4">
        <label for="role" class="mb-0 ps-3">{{ __('users.label_role') }}<span class="text-danger">*</span></label>
        <select class="form-select bg-white rounded-3 {{ $errors->has('role') ? 'is-invalid' : '' }}"
            name="role"
            id="role"
            >
            <option selected></option>
            <option value="Admin" {{ old('role', $user->role ?? '') == "Admin" ? 'selected' : '' }}>Admin</option>
            <option value="Operator" {{ old('role', $user->role ?? '') == "Operator" ? 'selected' : '' }}>Operator</option>
            <option value="Participant licitatii" {{ old('role', $user->role ?? '') == "Participant licitatii" ? 'selected' : '' }}>Participant licitatii</option>
        </select>
    </div>
    <div class="col-lg-6 mb-4" id="carrier_id_wrap">
        <label for="carrier_id" class="mb-0 ps-3">{{ __('users.label_carrier') }}<span class="text-danger">*</span></label>
        <select
            class="form-select bg-white rounded-3 {{ $errors->has('carrier_id') ? 'is-invalid' : '' }}"
            name="carrier_id"
            id="carrier_id"
        >
            <option value=""></option>
            @foreach (($carriers ?? []) as $carrier)
                <option value="{{ $carrier->id }}" @selected(old('carrier_id', $user->carrier_id ?? '') == $carrier->id)>
                    {{ $carrier->name }}
                </option>
            @endforeach
        </select>
        <div class="form-text ps-3">
            {{ __('users.carrier_help') }}
        </div>
    </div>
    <div class="col-lg-3 mb-4 text-center">
        <fieldset class="mb-4">
            <legend class="mb-0 fs-6">{{ __('users.label_active_account') }}<span class="text-danger">*</span></legend>
            <div class="d-flex py-1 justify-content-center">
                <div class="form-check me-4">
                    <input class="form-check-input" type="radio" value="1" name="activ" id="activ_da"
                        {{ old('activ', $user->activ ?? '') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="activ_da">{{ __('users.yes') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="0" name="activ" id="activ_nu"
                        {{ old('activ', $user->activ ?? '') == '0' ? 'checked' : '' }}>
                    <label class="form-check-label" for="activ_nu">{{ __('users.no') }}</label>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="col-lg-6 mb-4">
        <label for="password" class="mb-0 ps-3">
            {{ __('users.label_password') }} {!! isset($user) ? '' : '<span class="text-danger">*</span>' !!}
            <small class="text-muted">{{ isset($user) ? __('users.password_edit_hint') : '' }}</small>
        </label>
        <input
            id="password"
            type="password"
            class="form-control rounded-3 {{ $errors->has('password') ? 'is-invalid' : '' }}"
            name="password"
            id="password"
            autocomplete="new-password"
            {{ !isset($user) ? 'required' : '' }}
        >
        <div class="form-text ps-3">
            {{ __('users.password_min_hint') }}
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <label for="password_confirmation" class="mb-0 ps-3">
            {{ __('users.label_password_confirmation') }} {!! isset($user) ? '' : '<span class="text-danger">*</span>' !!}
        </label>
        <input
            id="password_confirmation"
            type="password"
            class="form-control rounded-3 {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
            name="password_confirmation"
            id="password_confirmation"
            autocomplete="new-password"
            {{ !isset($user) ? 'required' : '' }}
        >
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-2 d-flex justify-content-center">
        <button type="submit" class="btn btn-primary text-white me-3 rounded-3">
            <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
        </button>
        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('users.index')) }}">
            {{ __('users.cancel') }}
        </a>
    </div>
</div>

<script>
    (function () {
        const roleSelect = document.getElementById('role');
        const carrierWrap = document.getElementById('carrier_id_wrap');
        const carrierSelect = document.getElementById('carrier_id');

        function syncCarrierVisibility() {
            const isParticipant = roleSelect && roleSelect.value === 'Participant licitatii';
            if (carrierWrap) carrierWrap.style.display = isParticipant ? '' : 'none';
            if (carrierSelect) carrierSelect.required = !!isParticipant;
            if (!isParticipant && carrierSelect) carrierSelect.value = '';
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', syncCarrierVisibility);
        }
        syncCarrierVisibility();
    })();
</script>
