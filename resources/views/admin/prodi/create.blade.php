@extends('admin.layouts.app')

@section('title', 'Tambah Prodi — Admin')
@section('page_title', 'Tambah Prodi')
@section('page_subtitle', 'Input cepat master prodi dan relasi fakultas')

@section('content')
    <form method="post" action="{{ route('admin.prodi.store') }}">
        @csrf

        @include('admin.prodi._form', ['prodi' => null, 'fakultas' => $fakultas])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            <button class="btn btn-admin btn-admin-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection

