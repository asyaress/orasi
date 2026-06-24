@extends('admin.layouts.app')

@section('title', 'Tambah Orasi — Admin')
@section('page_title', 'Tambah Orasi')
@section('page_subtitle', 'Input judul, data dosen, video YouTube, dan file pendukung')

@section('content')
    <form method="post" action="{{ route('admin.orasi.store') }}" enctype="multipart/form-data">
        @csrf

        @include('admin.orasi._form', ['orasi' => null])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.orasi.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-yellow" type="submit">Simpan</button>
        </div>
    </form>
@endsection

