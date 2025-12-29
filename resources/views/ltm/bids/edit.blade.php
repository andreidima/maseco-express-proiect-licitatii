@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/bids.edit_title'),
        'subtitle' => $bid->lot->code ?? __('ltm/bids.fallback_subtitle'),
        'buttonRoute' => route('ltm.oferte.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.oferte.update', $bid) }}">
                @csrf
                @method('PUT')
                @include('ltm.bids._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection
