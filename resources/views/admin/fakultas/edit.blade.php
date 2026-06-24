@extends('admin.layouts.app')

@section('title', 'Edit Fakultas — Admin')
@section('page_title', 'Edit Fakultas')
@section('page_subtitle', 'Perbarui master fakultas')

@section('content')
    <form method="post" action="{{ route('admin.fakultas.update', $fakultas) }}">
        @csrf
        @method('PUT')

        @include('admin.fakultas._form', ['fakultas' => $fakultas])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.fakultas.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            <button class="btn btn-admin btn-admin-primary" type="submit">Simpan Perubahan</button>
        </div>
    </form>
@endsection

