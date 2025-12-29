@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/lots.edit_title'),
        'subtitle' => $lot->code,
        'buttonRoute' => route('ltm.loturi.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.loturi.update', $lot) }}">
                @csrf
                @method('PUT')
                @include('ltm.lots._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection
