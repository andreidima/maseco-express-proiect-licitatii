@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('settings/currencies.edit_title'),
        'subtitle' => $currency->code,
        'buttonRoute' => route('settings.currencies.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('settings.currencies.update', $currency) }}">
                @csrf
                @method('PUT')
                @include('settings.currencies._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection

