<!DOCTYPE html>
<html lang="id">
@include('admin.partials.head')
<body
    class="admin-body"
    @if (session('success')) data-flash-success="{{ e(session('success')) }}" @endif
    @if (session('warning')) data-flash-warning="{{ e(session('warning')) }}" @endif
    @if (session('error')) data-flash-error="{{ e(session('error')) }}" @endif
>
    <div class="admin-shell">
        @include('admin.partials.sidebar')

        <div class="admin-main d-flex flex-column">
            @include('admin.partials.topbar')

            <main class="admin-content flex-grow-1">
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <div class="fw-semibold mb-2">Periksa input berikut:</div>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @include('admin.partials.scripts')
</body>
</html>

