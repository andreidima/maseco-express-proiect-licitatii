@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-5">
            <div class="card bg-warning">
                <div class="card-header">{{ __('pages/errors.error') }}</div>

                <div class="card-body text-center">
                    {{ __('pages/errors.session_expired_code') }}
                    <br>
                    <br>
                    <a class="btn btn-primary border border-dark rounded-3" href="{{ url('/') }}">
                        {{ __('pages/errors.back_home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
