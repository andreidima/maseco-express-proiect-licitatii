@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/carriers.edit_title'),
        'subtitle' => $carrier->name,
        'buttonRoute' => route('ltm.transportatori.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.transportatori.update', $carrier) }}">
                @csrf
                @method('PUT')
                @include('ltm.carriers._form', ['buttonText' => __('ltm/carriers.update_button')])
            </form>
        </div>
    </div>
</div>
@endsection
