@extends('admin.layouts.app')

@section('title', 'Edit Pengumuman — Admin')
@section('page_title', 'Edit Pengumuman')
@section('page_subtitle', 'Perbarui konten dan status tayang')

@section('content')
    <form method="post" action="{{ route('admin.pengumuman.update', $pengumuman) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.pengumuman._form', ['pengumuman' => $pengumuman])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-yellow" type="submit">Simpan Perubahan</button>
        </div>
    </form>
@endsection
