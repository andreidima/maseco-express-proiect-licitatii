@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/routes.edit_title'),
        'subtitle' => $route->code,
        'buttonRoute' => route('ltm.curse.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.curse.update', $route) }}">
                @csrf
                @method('PUT')
                @include('ltm.routes._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection
