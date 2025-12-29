@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/clients.create_title'),
        'subtitle' => __('ltm/clients.create_subtitle'),
        'buttonRoute' => route('ltm.clienti.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.clienti.store') }}">
                @csrf
                @include('ltm.clients._form', ['buttonText' => __('ltm/clients.save_button')])
            </form>
        </div>
    </div>
</div>
@endsection
