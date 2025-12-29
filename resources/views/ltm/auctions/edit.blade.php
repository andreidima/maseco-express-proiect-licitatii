@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/auctions.edit_title'),
        'subtitle' => $auction->auction_number,
        'buttonRoute' => route('ltm.licitatii.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.licitatii.update', $auction) }}">
                @csrf
                @method('PUT')
                @include('ltm.auctions._form', ['buttonText' => __('ltm/auctions.update_button')])
            </form>
        </div>
    </div>
</div>
@endsection
