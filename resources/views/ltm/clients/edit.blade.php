@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/clients.edit_title'),
        'subtitle' => $client->name,
        'buttonRoute' => route('ltm.clienti.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.clienti.update', $client) }}">
                @csrf
                @method('PUT')
                @include('ltm.clients._form', ['buttonText' => __('ltm/clients.update_button')])
            </form>
        </div>
    </div>
</div>
@endsection
