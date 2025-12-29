<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/documents.form_type') }} <span class="text-danger">*</span></label>
        <select name="type" class="form-select" required>
            <option value="">{{ __('ltm/documents.option_select') }}</option>
            @foreach($types as $type)
                <option value="{{ $type }}" @selected(old('type', $document->type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/documents.form_contract') }}</label>
        <select name="contract_id" class="form-select">
            <option value="">{{ __('ltm/documents.option_choose_contract') }}</option>
            @foreach($contracts as $contract)
                <option value="{{ $contract->id }}" @selected(old('contract_id', $document->contract_id ?? '') == $contract->id)>{{ $contract->contract_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/documents.form_auction') }}</label>
        <select name="auction_id" class="form-select">
            <option value="">{{ __('ltm/documents.option_choose_auction') }}</option>
            @foreach($auctions as $auction)
                <option value="{{ $auction->id }}" @selected(old('auction_id', $document->auction_id ?? '') == $auction->id)>{{ $auction->auction_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/documents.form_client') }}</label>
        <select name="client_id" class="form-select">
            <option value="">{{ __('ltm/documents.option_choose_client') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $document->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/documents.form_carrier') }}</label>
        <select name="carrier_id" class="form-select">
            <option value="">{{ __('ltm/documents.option_choose_carrier') }}</option>
            @foreach($carriers as $carrier)
                <option value="{{ $carrier->id }}" @selected(old('carrier_id', $document->carrier_id ?? '') == $carrier->id)>{{ $carrier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ltm/documents.form_path') }}</label>
        <input type="text" name="file_path" value="{{ old('file_path', $document->file_path ?? '') }}" class="form-control" placeholder="documente/ltm_xx.pdf">
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/documents.form_description') }}</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $document->description ?? '') }}</textarea>
    </div>
    <div class="col-12">
        <label class="form-label">{{ __('ltm/documents.form_notes') }}</label>
        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $document->notes ?? '') }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary text-white">
        <i class="fa-solid fa-save me-1"></i> {{ $buttonText }}
    </button>
    <a href="{{ route('ltm.documente.index') }}" class="btn btn-outline-secondary">{{ __('ltm/common.cancel') }}</a>
</div>
