<!DOCTYPE html>
<html lang="id" class="orasi-booting">
@include('partials.head')

<body class="@yield('body_class')">
    @yield('body')

    {{-- @push('styles') dari view di dalam body; harus di bawah yield agar ikut ter-render --}}
    @stack('styles')

    @include('partials.scripts')
    @stack('scripts')
</body>
</html>
