@extends('admin.layouts.app')

@section('title', 'Edit Prodi — Admin')
@section('page_title', 'Edit Prodi')
@section('page_subtitle', 'Perbarui master prodi')

@section('content')
    <form method="post" action="{{ route('admin.prodi.update', $prodi) }}">
        @csrf
        @method('PUT')

        @include('admin.prodi._form', ['prodi' => $prodi, 'fakultas' => $fakultas])

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="{{ route('admin.prodi.index') }}" class="btn btn-outline-secondary btn-admin">Batal</a>
            <button class="btn btn-admin btn-admin-primary" type="submit">Simpan Perubahan</button>
        </div>
    </form>
@endsection

