@extends('admin.layouts.app')

@section('title', 'Tema Poster - Admin')
@section('page_title', 'Tema Poster')
@section('page_subtitle', 'Atur warna SVG/foto guru besar per tahun')

@section('content')
    <div class="admin-card">
        <div class="admin-card-body">
            <div class="admin-toolbar mb-0">
                <div class="text-muted small">
                    Setiap tahun punya tema otomatis. Simpan warna di sini kalau ingin mengganti tema poster tahun tertentu.
                </div>
            </div>

            <div class="poster-theme-grid mt-3">
                @foreach ($themeRows as $row)
                    @php
                        $year = $row['year'];
                        $theme = $row['theme'];
                        $palette = $row['palette'];
                        $cssVars = \App\Models\PosterTheme::cssVariables($palette);
                    @endphp
                    <form method="post" action="{{ route('admin.poster-themes.update', $year) }}" class="poster-theme-card">
                        @csrf
                        @method('PUT')

                        <div class="poster-theme-preview-wrap">
                            <div class="poster-theme-preview" style="{{ $cssVars }}">
                                <div class="poster-theme-preview-title">
                                    Orasi Ilmiah<br>Guru Besar
                                    <span>UNIVERSITAS MULAWARMAN</span>
                                </div>
                                <div class="poster-theme-preview-person">GB</div>
                                <div class="poster-theme-preview-footer">
                                    <strong>Nama Guru Besar</strong>
                                    <span>GURU BESAR</span>
                                    <span>BIDANG ILMU: Bidang Ilmu</span>
                                    <span>FAKULTAS</span>
                                </div>
                            </div>
                        </div>

                        <div class="poster-theme-form">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                <div>
                                    <div class="poster-theme-year">{{ $year }}</div>
                                    <div class="text-muted small">{{ $theme ? 'Custom tersimpan' : 'Tema otomatis' }}</div>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="theme-active-{{ $year }}" @checked(!$theme || $theme->is_active)>
                                    <label class="form-check-label small" for="theme-active-{{ $year }}">Aktif</label>
                                </div>
                            </div>

                            <label class="form-label" for="theme-name-{{ $year }}">Nama Tema</label>
                            <input id="theme-name-{{ $year }}" name="name" class="form-control mb-3" value="{{ old('name', $palette['name']) }}">

                            <div class="poster-theme-fields">
                                <label>
                                    <span>Frame</span>
                                    <input type="color" name="frame_background" value="{{ old('frame_background', $palette['frame_background']) }}">
                                </label>
                                <label>
                                    <span>Bidang</span>
                                    <input type="color" name="frame_highlight" value="{{ old('frame_highlight', $palette['frame_highlight']) }}">
                                </label>
                                <label>
                                    <span>Footer Atas</span>
                                    <input type="color" name="footer_start" value="{{ old('footer_start', $palette['footer_start']) }}">
                                </label>
                                <label>
                                    <span>Footer Bawah</span>
                                    <input type="color" name="footer_end" value="{{ old('footer_end', $palette['footer_end']) }}">
                                </label>
                                <label>
                                    <span>Teks</span>
                                    <input type="color" name="text_color" value="{{ old('text_color', $palette['text_color']) }}">
                                </label>
                            </div>

                            <button class="btn btn-admin btn-admin-primary w-100 mt-3" type="submit">
                                <i class="bi bi-check-lg"></i> Simpan Tema {{ $year }}
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection
