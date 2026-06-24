@extends('layouts.app')

@section('body_class', 'page-home')

@section('title', 'Orasi Ilmiah Guru Besar Universitas Mulawarman')
@section('meta_description', 'Informasi agenda, video YouTube, dokumen, dan arsip Orasi Ilmiah Guru Besar Universitas Mulawarman.')

@section('body')
    <div id="wrapper">
        @include('partials.preloader')

        @include('partials.header')

        @include('partials.home.content')

        @include('partials.footer')
    </div>

    @include('partials.home.chatbot')
@endsection

@push('scripts')
    <script>
        (function () {
            function resetHomeHeader() {
                var header = document.querySelector('header.header-full.transparent');
                if (!header) {
                    return;
                }

                if (window.scrollY <= 24) {
                    header.classList.remove('smaller');
                    header.classList.remove('scroll-down');
                    header.classList.remove('nav-up');
                    header.style.background = 'transparent';
                    header.style.backgroundColor = 'transparent';
                }
            }

            document.addEventListener('DOMContentLoaded', resetHomeHeader);
            window.addEventListener('pageshow', resetHomeHeader);
            window.addEventListener('scroll', resetHomeHeader, { passive: true });
        })();
    </script>
@endpush
