@extends('layouts.app')

@section('body_class', 'page-guru-besar-show')

@section('title', ($pageTitle ?? $guruBesar->nama) . ' | Orasi Ilmiah Guru Besar Universitas Mulawarman')
@section('meta_description', $pageDescription ?? 'Profil guru besar, video, dan dokumen orasi ilmiah Universitas Mulawarman.')

@section('body')
    <div id="wrapper">
        @include('partials.preloader')

        @include('partials.header')

        @include('partials.guru-besar.detail-content')

        @include('partials.footer')
    </div>
@endsection
