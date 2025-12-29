@extends('layouts.app')

@section('content')
<div class="container">
    @include('ltm.partials.header', [
        'title' => __('ltm/documents.edit_title'),
        'subtitle' => $document->type,
        'buttonRoute' => route('ltm.documente.index'),
        'buttonLabel' => __('ltm/common.back_to_list'),
        'badges' => [],
    ])

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @include('errors.errors')
            <form method="POST" action="{{ route('ltm.documente.update', $document) }}">
                @csrf
                @method('PUT')
                @include('ltm.documents._form', ['buttonText' => __('ltm/common.update')])
            </form>
        </div>
    </div>
</div>
@endsection
