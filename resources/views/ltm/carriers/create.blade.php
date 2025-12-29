@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/carriers.create_title'),
        'subtitle' => __('ltm/carriers.create_subtitle'),
        'buttonRoute' => route('ltm.transportatori.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.transportatori.store') }}">
                @csrf
                @include('ltm.carriers._form', ['buttonText' => __('ltm/carriers.save_button')])
            </form>
        </div>
    </div>
</div>
@endsection
