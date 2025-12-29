@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="shadow-lg" style="border-radius: 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-users me-1"></i> {{ __('users.details_title') }}
                    </span>
                </div>

                <div class="card-body border border-secondary p-4" style="border-radius: 0px 0px 40px 40px;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_name') }}:</strong> {{ $user->name }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_email') }}:</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_phone') }}:</strong> {{ $user->telefon }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_role') }}:</strong>{{ $user->role }}
                        </div>
                        @if ($user->role === 'Participant licitatii')
                            <div class="col-md-6 mb-3">
                                <strong>{{ __('users.details_carrier') }}:</strong> {{ $user->carrier?->name ?? __('users.details_carrier_unassigned') }}
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_account_status') }}:</strong>
                            @if ($user->activ == 0)
                                <span class="text-danger">{{ __('users.status_closed') }}</span>
                            @else
                                <span class="text-success">{{ __('users.status_open') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_created_at') }}:</strong> {{ $user->created_at?->format('d.m.Y H:i') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>{{ __('users.details_updated_at') }}:</strong> {{ $user->updated_at?->format('d.m.Y H:i') }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary text-white me-3 rounded-3">
                            <i class="fa-solid fa-edit me-1"></i> {{ __('users.edit') }}
                        </a>
                        <a class="btn btn-secondary rounded-3" href="{{ Session::get('returnUrl', route('users.index')) }}">
                            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('users.back') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
