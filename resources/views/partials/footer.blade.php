<footer>
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-4 col-sm-6">
                <div class="d-inline-flex flex-column align-items-start gap-3">
                    @include('partials.brand-logos')
                    <span class="text-white fs-5 fw-bold" style="letter-spacing: -.03em;">Portal Orasi Ilmiah Guru Besar</span>
                </div>
                <div class="spacer-20"></div>
                <p>
                    Portal Orasi Ilmiah Guru Besar Universitas Mulawarman menghimpun agenda, profil orator,
                    dokumentasi video, dokumen akademik, dan statistik arsip dalam satu kanal informasi institusional.
                </p>

                <div class="widget">
                    <h5>Akses Cepat</h5>
                    <div class="social-icons">
                        <a href="{{ route('home') }}" aria-label="Beranda"><i class="fa-solid fa-house"></i></a>
                        <a href="{{ route('portal.guru-besar') }}" aria-label="Guru Besar"><i class="fa-solid fa-user-graduate"></i></a>
                        <a href="{{ route('portal.video-orasi') }}" aria-label="Video Orasi"><i class="fa-brands fa-youtube"></i></a>
                        <a href="{{ route('portal.dokumen-orasi') }}" aria-label="Dokumen Orasi"><i class="fa-solid fa-file-lines"></i></a>
                        <a href="{{ route('portal.statistik') }}" aria-label="Statistik"><i class="fa-solid fa-chart-column"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 order-lg-1 order-sm-2">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="widget">
                            <h5>Navigasi Akademik</h5>
                            <ul>
                                <li><a href="{{ route('home') }}">Beranda</a></li>
                                <li><a href="{{ route('portal.guru-besar') }}">Guru Besar</a></li>
                                <li><a href="{{ route('portal.daftar-orasi') }}">Agenda Orasi</a></li>
                                <li><a href="{{ route('portal.video-orasi') }}">Video Orasi</a></li>
                                <li><a href="{{ route('portal.statistik') }}">Statistik</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="widget">
                            <h5>Dokumentasi</h5>
                            <ul>
                                <li><a href="{{ route('portal.dokumen-orasi') }}">Dokumen Orasi</a></li>
                                <li><a href="{{ route('portal.video-orasi') }}">Dokumentasi Video</a></li>
                                <li><a href="{{ route('portal.daftar-orasi') }}">Arsip Agenda</a></li>
                                <li><a href="{{ route('portal.guru-besar') }}">Direktori Orator</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 order-lg-2 order-sm-1">
                <div class="widget">
                    <h5>Informasi Institusi</h5>
                    <div class="de-icon-text mb20">
                        <img src="{{ asset('images/svg/map-pin-svgrepo-com-white.svg') }}" alt="">
                        <div class="d-text">
                            <h4>Lokasi</h4>
                            Kampus Universitas Mulawarman, Samarinda, Kalimantan Timur
                        </div>
                    </div>

                    <div class="de-icon-text mb20">
                        <img src="{{ asset('images/svg/email-address-svgrepo-com-white.svg') }}" alt="">
                        <div class="d-text">
                            <h4>Kanal Informasi</h4>
                            Portal publik untuk publikasi agenda, media, dan arsip orasi ilmiah guru besar.
                        </div>
                    </div>

                    <div class="de-icon-text">
                        <img src="{{ asset('images/svg/phone-svgrepo-com-white.svg') }}" alt="">
                        <div class="d-text">
                            <h4>Lingkup Layanan</h4>
                            Penyajian informasi akademik, dokumentasi ilmiah, dan statistik arsip kelembagaan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="subfooter">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="de-flex">
                        <div class="de-flex-col">
                            Copyright {{ now()->year }} - Portal Orasi Ilmiah Guru Besar Universitas Mulawarman
                        </div>
                        <ul class="menu-simple">
                            <li><a href="{{ route('portal.daftar-orasi') }}">Agenda</a></li>
                            <li><a href="{{ route('portal.guru-besar') }}">Guru Besar</a></li>
                            <li><a href="{{ route('portal.statistik') }}">Statistik</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
