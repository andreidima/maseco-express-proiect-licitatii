@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/contracts.create_title'),
        'subtitle' => __('ltm/contracts.create_subtitle'),
        'buttonRoute' => route('ltm.contracte.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.contracte.store') }}">
                @csrf
                @include('ltm.contracts._form', ['buttonText' => __('ltm/common.save')])
            </form>
        </div>
    </div>
</div>
@endsection
