@extends('admin.layouts.app')

@section('title', 'Edit Guru Besar — Admin')
@section('page_title', 'Edit Guru Besar')
@section('page_subtitle', 'Perbarui master data guru besar')

@section('content')
    <form method="post" action="{{ route('admin.guru-besar.update', $guruBesar) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.guru-besar._form')

        <div class="admin-form-actions">
            <a href="{{ route('admin.guru-besar.index') }}" class="btn btn-outline-secondary btn-admin">Kembali</a>
            @if ($guruBesar->orasiIlmiah)
                <a href="{{ route('admin.orasi-ilmiah.show', $guruBesar->orasiIlmiah) }}" class="btn btn-outline-secondary btn-admin">Lihat Orasi</a>
            @endif
            <button class="btn btn-admin btn-admin-primary" type="submit"><i class="bi bi-check-lg"></i> Simpan</button>
        </div>
    </form>
@endsection
