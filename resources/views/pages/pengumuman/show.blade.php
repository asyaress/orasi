@extends('layouts.app')

@section('body_class', 'page-pengumuman page-pengumuman-detail')
@section('title', $pengumuman->judul . ' | Pengumuman Orasi UNMUL')
@section('meta_description', $pengumuman->ringkasan ?: \Illuminate\Support\Str::limit(strip_tags($pengumuman->konten), 155))

@section('body')
    <div id="wrapper">
        @include('partials.preloader')
        @include('partials.header')

        <div class="no-bottom no-top" id="content">
            <div id="top"></div>

            <section class="orasi-page-banner has-video text-light pengumuman-detail-video-banner" style="--orasi-page-banner-bg: url('{{ $heroBackground }}');">
                <div class="orasi-page-banner-video" aria-hidden="true">
                    <iframe
                        src="{{ $heroYoutubeEmbedUrl }}"
                        title="Video latar Portal Orasi"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        loading="lazy"
                        tabindex="-1"
                    ></iframe>
                </div>
                <div class="container position-relative text-center orasi-page-banner-shell">
                    <h1 class="orasi-page-banner-title pengumuman-detail-title">{{ $pengumuman->judul }}</h1>
                </div>
            </section>

            <section class="pengumuman-detail-content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-9 col-lg-10">
                            <article class="pengumuman-article">
                                @if ($pengumuman->ringkasan)
                                    <p class="pengumuman-lead">{{ $pengumuman->ringkasan }}</p>
                                @endif
                                <div class="pengumuman-rich-content">{!! $pengumuman->konten !!}</div>
                            </article>

                            <div class="pengumuman-back">
                                <a href="{{ route('portal.pengumuman.index') }}"><i class="fa fa-arrow-left"></i> Kembali ke semua pengumuman</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @if ($related->isNotEmpty())
                <section class="pengumuman-related no-top">
                    <div class="container">
                        <div class="pengumuman-section-title">
                            <div class="subtitle s2 mb-2">Informasi Lainnya</div>
                            <h2>Pengumuman lainnya</h2>
                        </div>
                        <div class="row g-4">
                            @foreach ($related as $item)
                                <div class="col-lg-4 col-md-6">
                                    <article class="pengumuman-card h-100">
                                        <a class="pengumuman-card-media" href="{{ route('portal.pengumuman.show', $item) }}">
                                            @if ($item->cover_url)
                                                <img src="{{ $item->cover_url }}" alt="Cover {{ $item->judul }}" loading="lazy">
                                            @else
                                                <span class="pengumuman-cover-placeholder"><i class="fa fa-bullhorn"></i></span>
                                            @endif
                                        </a>
                                        <div class="pengumuman-card-body">
                                            <div class="pengumuman-date">{{ $item->published_label }}</div>
                                            <h3><a href="{{ route('portal.pengumuman.show', $item) }}">{{ $item->judul }}</a></h3>
                                            <p>{{ $item->ringkasan ?: \Illuminate\Support\Str::limit(strip_tags($item->konten), 110) }}</p>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        </div>

        @include('partials.footer')
    </div>
@endsection
