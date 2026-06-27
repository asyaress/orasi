@extends('admin.layouts.app')

@section('title', 'Tambah Pengumuman — Admin')
@section('page_title', 'Tambah Pengumuman')
@section('page_subtitle', 'Buat pengumuman ringkas dan jelas')

@section('content')
    <form method="post" action="{{ route('admin.pengumuman.store') }}" enctype="multipart/form-data">
        @csrf

        @include('admin.pengumuman._form', ['pengumuman' => null])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-yellow" type="submit">Simpan</button>
        </div>
    </form>
@endsection
