@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('settings/currencies.create_title'),
        'subtitle' => __('settings/currencies.create_subtitle'),
        'buttonRoute' => route('settings.currencies.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('settings.currencies.store') }}">
                @csrf
                @include('settings.currencies._form', ['buttonText' => __('ltm/common.save')])
            </form>
        </div>
    </div>
</div>
@endsection

