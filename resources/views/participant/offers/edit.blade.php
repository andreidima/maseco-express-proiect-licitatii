@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('participant/offers.edit_title'),
        'subtitle' => __('participant/offers.edit_subtitle'),
        'badges' => [
            __('participant/offers.badge_auction', ['number' => e($bid->auction->auction_number ?? '-')]),
            __('participant/offers.badge_lot', ['code' => e($bid->lot->code ?? '-')]),
        ],
    ])

    @include('errors.errors')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('participant.oferte.update', $bid) }}">
                @csrf
                @method('PUT')
                @include('participant.offers._form', [
                    'bid' => $bid,
                    'buttonText' => __('participant/offers.update_button'),
                ])
            </form>
        </div>
    </div>
</div>
@endsection
