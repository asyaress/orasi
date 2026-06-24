@extends('errors::orasi')

@section('title', '503 — Service Unavailable | Portal Orasi UNMUL')

@section('code', '503')

@section('heading', 'Service Unavailable')

@section('copy')
    Layanan Portal Orasi Ilmiah sedang tidak tersedia untuk sementara. Tim kami mungkin sedang melakukan pemeliharaan, pembaruan, atau penyesuaian sistem.
@endsection

@php
    $maintenanceMessage = null;

    if (isset($exception) && filled($exception->getMessage())) {
        $defaultMessage = __('Service Unavailable');

        if ($exception->getMessage() !== $defaultMessage && $exception->getMessage() !== 'Service Unavailable') {
            $maintenanceMessage = $exception->getMessage();
        }
    }
@endphp

@section('note')
    @if ($maintenanceMessage)
        {{ $maintenanceMessage }}
    @endif
@endsection

@section('actions')
    <button type="button" class="orasi-error-btn orasi-error-btn--primary" onclick="window.location.reload()">
        Muat Ulang Halaman
    </button>
    <a href="{{ url('/') }}" class="orasi-error-btn orasi-error-btn--ghost">
        Kembali ke Beranda
    </a>
@endsection
