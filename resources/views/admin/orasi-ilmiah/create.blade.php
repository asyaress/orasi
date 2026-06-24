@extends('admin.layouts.app')

@section('title', 'Tambah Orasi Ilmiah — Admin')
@section('page_title', 'Tambah Orasi Ilmiah')
@section('page_subtitle', 'Buat event orasi dan kelola daftar Guru Besar')

@section('content')
    <form method="post" action="{{ route('admin.orasi-ilmiah.store') }}" enctype="multipart/form-data">
        @csrf

        @include('admin.orasi-ilmiah._form', ['orasi' => null])

        <div class="admin-form-actions">
            <a href="{{ route('admin.orasi-ilmiah.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            <button class="btn btn-admin btn-admin-primary" type="submit"><i class="bi bi-check-lg"></i> Simpan</button>
        </div>
    </form>
@endsection

