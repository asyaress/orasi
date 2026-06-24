@extends('admin.layouts.app')

@section('title', 'Edit Orasi Ilmiah — Admin')
@section('page_title', 'Edit Orasi Ilmiah')
@section('page_subtitle', 'Perbarui event orasi, media, dan status')

@section('content')
    <form method="post" action="{{ route('admin.orasi-ilmiah.update', $orasi) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.orasi-ilmiah._form', ['orasi' => $orasi])

        <div class="admin-form-actions">
            <a href="{{ route('admin.orasi-ilmiah.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            <button class="btn btn-admin btn-admin-primary" type="submit"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
        </div>
    </form>
@endsection

