@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/contracts.edit_title'),
        'subtitle' => $contract->contract_number,
        'buttonRoute' => route('ltm.contracte.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.contracte.update', $contract) }}">
                @csrf
                @method('PUT')
                @include('ltm.contracts._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection
