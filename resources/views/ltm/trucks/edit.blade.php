@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/trucks.edit_title'),
        'subtitle' => $truck->plate_number,
        'buttonRoute' => route('ltm.camioane.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.camioane.update', $truck) }}">
                @csrf
                @method('PUT')
                @include('ltm.trucks._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection
