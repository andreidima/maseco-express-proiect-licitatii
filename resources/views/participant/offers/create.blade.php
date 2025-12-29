@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('participant/offers.create_title'),
        'subtitle' => __('participant/offers.create_subtitle'),
        'badges' => [
            __('participant/offers.badge_carrier_preselected'),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('participant.oferte.store') }}">
                @csrf
                @include('participant.offers._form', [
                    'openLots' => $openLots,
                    'preselectedLotId' => $preselectedLotId,
                    'buttonText' => __('participant/offers.create_button'),
                ])
            </form>
        </div>
    </div>
</div>
@endsection
