<div class="p-4 mb-3 text-white rounded-4 shadow ltm-hero">
    <div class="d-flex flex-wrap justify-content-between align-items-start">
        <div class="mb-2">
            <h2 class="fw-bold mb-2">{{ $title }}</h2>
            @if (!empty($subtitle))
                <p class="mb-2 opacity-75">{{ $subtitle }}</p>
            @endif
            <div class="d-flex flex-wrap gap-2">
                @foreach ($badges ?? [] as $badge)
                    <span class="badge bg-light text-dark shadow-sm">
                        {!! $badge !!}
                    </span>
                @endforeach
            </div>
        </div>
        @if (!empty($buttonRoute))
            <div class="text-end">
                <a href="{{ $buttonRoute }}" class="btn btn-lg btn-light text-primary fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-2"></i>{{ $buttonLabel ?? __('ltm/common.add') }}
                </a>
            </div>
        @endif
    </div>
</div>
