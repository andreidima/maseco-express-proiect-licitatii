@extends ('layouts.app')

@section('content')
<div class="mx-3 px-3 card" style="border-radius: 40px 40px 40px 40px;">
    <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
        <div class="col-lg-3">
            <span class="badge culoare1 fs-5">
                <i class="fa-solid fa-users"></i> {{ __('users.title') }}
            </span>
        </div>

        {{-- Search form --}}
        <div class="col-lg-6">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="row mb-1 custom-search-form justify-content-center">
                    <div class="col-lg-6">
                        <input type="text" class="form-control rounded-3" id="searchNume" name="searchNume" placeholder="{{ __('users.search_name') }}" value="{{ $searchNume }}">
                    </div>
                    <div class="col-lg-6">
                        <input type="text" class="form-control rounded-3" id="searchTelefon" name="searchTelefon" placeholder="{{ __('users.search_phone') }}" value="{{ $searchTelefon }}">
                    </div>
                </div>
                <div class="row custom-search-form justify-content-center">
                    <div class="col-lg-4">
                        <button class="btn btn-sm w-100 btn-primary text-white border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>{{ __('users.search') }}
                        </button>
                    </div>
                    <div class="col-lg-4">
                        <a class="btn btn-sm w-100 btn-secondary text-white border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>{{ __('users.reset_search') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Buton to add new user --}}
        <div class="col-lg-3 text-end">
            <a class="btn btn-sm btn-success text-white border border-dark rounded-3 col-md-8" href="{{ url()->current() }}/adauga" role="button">
                <i class="fas fa-user-plus text-white me-1"></i> {{ __('users.add_user') }}
            </a>
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body px-0 py-3">

        @include ('errors.errors')

        <div class="table-responsive rounded">
            <table class="table table-striped table-hover rounded" aria-label="{{ __('users.table_aria') }}">
                <thead class="text-white rounded">
                    <tr class="thead-danger" style="padding:2rem">
                        <th scope="col" class="text-white culoare2" width="5%"><i class="fa-solid fa-hashtag"></i></th>
                        <th scope="col" class="text-white culoare2" width="25%"><i class="fa-solid fa-user me-1"></i> {{ __('users.col_name') }}</th>
                        <th scope="col" class="text-white culoare2" width="15%"><i class="fa-solid fa-phone me-1"></i> {{ __('users.col_phone') }}</th>
                        <th scope="col" class="text-white culoare2" width="25%"><i class="fa-solid fa-envelope me-1"></i> {{ __('users.col_email') }}</th>
                        <th scope="col" class="text-white culoare2" width="10%"><i class="fa-solid fa-user-tag me-1"></i> {{ __('users.col_role') }}</th>
                        <th scope="col" class="text-white culoare2" width="10%"><i class="fa-solid fa-toggle-on me-1"></i> {{ __('users.col_account_status') }}</th>
                        <th scope="col" class="text-white culoare2 text-end" width="10%"><i class="fa-solid fa-cogs me-1"></i> {{ __('users.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td align="">
                                {{ ($users ->currentpage()-1) * $users ->perpage() + $loop->index + 1 }}
                            </td>
                            <td class="">
                                {{ $user->name }}
                            </td>
                            <td class="">
                                {{ $user->telefon }}
                            </td>
                            <td class="">
                                {{ $user->email }}
                            </td>
                            <td class="">
                                {{ $user->role }}
                            </td>
                            <td>
                                @if ($user->activ == 0)
                                    <span class="text-danger">{{ __('users.status_closed') }}</span>
                                @else
                                    <span class="text-success">{{ __('users.status_open') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end py-0">
                                    <a href="{{ $user->path() }}" class="flex me-1" aria-label="{{ __('users.action_view', ['name' => $user->name]) }}">
                                        <span class="badge bg-success"><i class="fa-solid fa-eye"></i></span>
                                    </a>
                                    <a href="{{ $user->path('edit') }}" class="flex me-1" aria-label="{{ __('users.action_edit', ['name' => $user->name]) }}">
                                        <span class="badge bg-primary"><i class="fa-solid fa-edit"></i></span>
                                    </a>
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#stergeUtilizator{{ $user->id }}"
                                       aria-label="{{ __('users.action_delete', ['name' => $user->name]) }}">
                                        <span class="badge bg-danger"><i class="fa-solid fa-trash"></i></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fa-solid fa-users-slash fa-2x mb-3 d-block"></i>
                                <p class="mb-0">{{ __('users.empty') }}</p>
                                @if($searchNume || $searchTelefon)
                                    <p class="small mb-0 mt-2">{{ __('users.empty_hint') }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                {{ $users->appends(Request::except('page'))->links() }}
            </ul>
        </nav>
    </div>
</div>

{{-- Modals to delete users --}}
@foreach ($users as $user)
    <div class="modal fade text-dark" id="stergeUtilizator{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="stergeUtilizatorLabel{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="stergeUtilizatorLabel{{ $user->id }}">
                        <i class="fa-solid fa-user-minus me-1"></i> {{ __('users.delete_title', ['name' => $user->name]) }}
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    {{ __('users.delete_confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('users.cancel') }}</button>
                    <form method="POST" action="{{ $user->path('destroy') }}">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger text-white">
                            <i class="fa-solid fa-trash me-1"></i> {{ __('users.delete_user') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
