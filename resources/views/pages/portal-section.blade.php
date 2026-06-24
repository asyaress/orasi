@extends('layouts.app')

@section('body_class', 'page-portal-section')

@section('title', ($pageTitle ?? 'Portal Orasi') . ' | Orasi Ilmiah Guru Besar Universitas Mulawarman')
@section('meta_description', $pageDescription ?? 'Portal Orasi Ilmiah Guru Besar Universitas Mulawarman.')

@section('body')
    <div id="wrapper">
        @include('partials.preloader')

        @include('partials.header')

        @include('partials.home.content')

        @include('partials.footer')
    </div>
@endsection
