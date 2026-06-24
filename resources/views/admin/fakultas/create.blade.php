@extends('admin.layouts.app')

@section('title', 'Tambah Fakultas — Admin')
@section('page_title', 'Tambah Fakultas')
@section('page_subtitle', 'Input cepat master fakultas')

@section('content')
    <form method="post" action="{{ route('admin.fakultas.store') }}">
        @csrf

        @include('admin.fakultas._form', ['fakultas' => null])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.fakultas.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            <button class="btn btn-admin btn-admin-primary" type="submit">Simpan</button>
        </div>
    </form>
@endsection

