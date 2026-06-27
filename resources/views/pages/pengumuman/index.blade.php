@extends('layouts.app')

@section('body_class', 'page-pengumuman')
@section('title', 'Pengumuman | Orasi Ilmiah Guru Besar Universitas Mulawarman')
@section('meta_description', 'Pengumuman terbaru seputar Orasi Ilmiah Guru Besar Universitas Mulawarman.')

@section('body')
    <div id="wrapper">
        @include('partials.preloader')
        @include('partials.header')

        <div class="no-bottom no-top" id="content">
            <div id="top"></div>

            <section class="orasi-page-banner has-video text-light" style="--orasi-page-banner-bg: url('{{ $heroBackground }}');">
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
                    <div class="orasi-page-banner-kicker">Portal Orasi Unmul</div>
                    <h1 class="orasi-page-banner-title">Pengumuman</h1>
                    <p class="orasi-page-banner-copy">Informasi resmi dan kabar terbaru Orasi Ilmiah Guru Besar Universitas Mulawarman.</p>
                </div>
            </section>

            <section class="pengumuman-list-section">
                <div class="container">
                    @if ($tag || $search)
                        <div class="pengumuman-filter-active mb-4">
                            Menampilkan hasil {{ $tag ? 'tag: '.$tag : 'pencarian: '.$search }}
                            <a href="{{ route('portal.pengumuman.index') }}">Hapus filter</a>
                        </div>
                    @endif

                    @if ($items->count())
                        <div class="row g-4 justify-content-center">
                            @foreach ($items as $item)
                                <div class="col-xl-7 col-lg-8 col-md-10">
                                    <article class="pengumuman-card pengumuman-card--featured h-100">
                                        <a class="pengumuman-card-media" href="{{ route('portal.pengumuman.show', $item) }}" aria-label="Baca {{ $item->judul }}">
                                            @if ($item->cover_url)
                                                <img src="{{ $item->cover_url }}" alt="Cover {{ $item->judul }}" loading="lazy">
                                            @else
                                                <span class="pengumuman-cover-placeholder"><i class="fa fa-bullhorn"></i></span>
                                            @endif
                                        </a>
                                        <div class="pengumuman-card-body">
                                            <div class="pengumuman-date"><i class="fa fa-calendar-o"></i> {{ $item->published_label }}</div>
                                            <h2><a href="{{ route('portal.pengumuman.show', $item) }}">{{ $item->judul }}</a></h2>
                                            <a class="pengumuman-read-more" href="{{ route('portal.pengumuman.show', $item) }}">Baca selengkapnya <i class="fa fa-arrow-right"></i></a>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        <div class="pengumuman-pagination mt-5">
                            {{ $items->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="pengumuman-empty">
                            <i class="fa fa-search"></i>
                            <h2>Pengumuman belum ditemukan</h2>
                            <p>Coba gunakan kata kunci lain atau hapus filter yang aktif.</p>
                            <a class="btn-main" href="{{ route('portal.pengumuman.index') }}">Lihat semua pengumuman</a>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        @include('partials.footer')
    </div>
@endsection
